const DB_NAME = 'cmih_merchandiser_offline_sync';
const DB_VERSION = 1;
const STORE_NAME = 'queued_forms';

function openDb() {
    return new Promise((resolve, reject) => {
        if (!('indexedDB' in window)) {
            reject(new Error('IndexedDB unavailable'));
            return;
        }

        const request = indexedDB.open(DB_NAME, DB_VERSION);
        request.onerror = () => reject(request.error);
        request.onsuccess = () => resolve(request.result);
        request.onupgradeneeded = () => {
            const db = request.result;
            if (!db.objectStoreNames.contains(STORE_NAME)) {
                db.createObjectStore(STORE_NAME, { keyPath: 'id' });
            }
        };
    });
}

function transaction(storeMode = 'readonly') {
    return openDb().then((db) => {
        const tx = db.transaction(STORE_NAME, storeMode);
        return {
            store: tx.objectStore(STORE_NAME),
            done: new Promise((resolve, reject) => {
                tx.oncomplete = resolve;
                tx.onerror = () => reject(tx.error);
                tx.onabort = () => reject(tx.error);
            }),
        };
    });
}

function requestToPromise(request) {
    return new Promise((resolve, reject) => {
        request.onsuccess = () => resolve(request.result);
        request.onerror = () => reject(request.error);
    });
}

function ensureSyncFields(form, queued = false) {
    const now = new Date().toISOString();
    const prefix = form.dataset.offlineSyncForm || 'form';
    const tokenInput = form.querySelector('[name="sync_token"]');
    const sourceInput = form.querySelector('[name="sync_source"]');
    const recordedInput = form.querySelector('[name="client_recorded_at"]');

    if (tokenInput && !tokenInput.value) {
        tokenInput.value = `${prefix}-${Date.now()}-${Math.random().toString(36).slice(2, 10)}`;
    }

    if (sourceInput) {
        sourceInput.value = queued ? 'queued' : (sourceInput.value || 'live');
    }

    if (recordedInput && !recordedInput.value) {
        recordedInput.value = now;
    }
}

async function serializeForm(form) {
    const fields = [];
    const formData = new FormData(form);

    for (const [name, value] of formData.entries()) {
        if (value instanceof File) {
            if (!value.name || value.size === 0) {
                continue;
            }

            fields.push({
                name,
                kind: 'file',
                file: value,
                filename: value.name,
                type: value.type,
            });
        } else {
            fields.push({
                name,
                kind: 'field',
                value,
            });
        }
    }

    return {
        id: form.querySelector('[name="sync_token"]')?.value || `queued-${Date.now()}-${Math.random().toString(36).slice(2, 10)}`,
        action: form.action,
        method: (form.method || 'POST').toUpperCase(),
        formType: form.dataset.offlineSyncForm || 'form',
        queuedAt: new Date().toISOString(),
        fields,
    };
}

async function saveQueuedForm(form) {
    ensureSyncFields(form, true);
    const payload = await serializeForm(form);
    const tx = await transaction('readwrite');
    tx.store.put(payload);
    await tx.done;
    return payload;
}

async function allQueuedForms() {
    const tx = await transaction('readonly');
    return requestToPromise(tx.store.getAll());
}

async function deleteQueuedForm(id) {
    const tx = await transaction('readwrite');
    tx.store.delete(id);
    await tx.done;
}

function rebuildFormData(item) {
    const formData = new FormData();
    item.fields.forEach((field) => {
        if (field.kind === 'file') {
            formData.append(field.name, field.file, field.filename);
            return;
        }

        formData.append(field.name, field.value ?? '');
    });

    formData.set('sync_source', 'offline_retry');
    if (!formData.get('client_recorded_at')) {
        formData.set('client_recorded_at', item.queuedAt);
    }

    return formData;
}

async function replayQueuedForms() {
    if (!navigator.onLine) return;

    let items = [];
    try {
        items = await allQueuedForms();
    } catch (error) {
        console.warn('Offline queue unavailable:', error);
        return;
    }

    for (const item of items) {
        try {
            const response = await fetch(item.action, {
                method: item.method,
                body: rebuildFormData(item),
                credentials: 'same-origin',
                headers: {
                    Accept: 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (response.ok || response.redirected) {
                await deleteQueuedForm(item.id);
            }
        } catch (error) {
            console.warn('Offline queued form replay failed:', error);
            return;
        }
    }
}

function notifyQueued(message) {
    const banner = document.createElement('div');
    banner.textContent = message;
    banner.style.cssText = 'position:fixed;left:16px;right:16px;bottom:16px;z-index:9999;padding:14px 16px;border-radius:14px;background:#063b2b;color:#d1fae5;border:1px solid rgba(52,211,153,.45);font:700 13px system-ui,sans-serif;box-shadow:0 20px 50px rgba(0,0,0,.35);';
    document.body.appendChild(banner);
    window.setTimeout(() => banner.remove(), 5200);
}

window.addEventListener('submit', async (event) => {
    const form = event.target;
    if (!(form instanceof HTMLFormElement) || !form.matches('[data-offline-sync-form]')) {
        return;
    }

    ensureSyncFields(form, false);

    if (navigator.onLine) {
        return;
    }

    event.preventDefault();
    try {
        await saveQueuedForm(form);
        notifyQueued('You are offline. This form has been queued and will sync automatically when the connection returns.');
    } catch (error) {
        notifyQueued('Offline save failed. Please keep this page open and try again when the connection returns.');
        console.warn('Unable to queue offline form:', error);
    }
}, true);

window.addEventListener('online', replayQueuedForms);
window.addEventListener('load', replayQueuedForms);

window.CMIHMerchandiserOfflineSync = {
    replayQueuedForms,
    allQueuedForms,
};
