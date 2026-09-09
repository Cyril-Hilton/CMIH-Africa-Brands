const { chromium } = require('playwright');
const assert = require('node:assert/strict');

(async () => {
    const browser = await chromium.launch({ channel: 'chrome', headless: true });
    const page = await browser.newPage({ viewport: { width: 1440, height: 1000 } });
    const errors = [];
    page.on('pageerror', error => errors.push({ message: error.message, url: page.url(), stack: error.stack }));
    const base = process.env.QA_BASE_URL || 'http://127.0.0.1:8018';
    assert(new URL(base).hostname === '127.0.0.1', 'This test uses local fixtures only.');
    try {
        await page.goto(`${base}/login`);
        await page.locator('#email').fill(process.env.QA_EMAIL || 'superadmin@cmih.africa');
        await page.locator('#password').fill(process.env.QA_PASSWORD || 'Pass123');
        await page.getByRole('button', { name: 'Access Dashboard', exact: true }).click();
        await page.waitForURL(url => !url.pathname.endsWith('/login'));
        await page.goto(`${base}/merchandisers/client/dashboard`);
        for (const [label, view] of [['Category KPIs', 'category-kpi'], ['User Performance', 'user-performance'], ['Price & Promo', 'price-promo'], ['Executive Summary', 'executive']]) {
            await page.getByRole('link', { name: label, exact: true }).click();
            await page.waitForURL(url => url.searchParams.get('view') === view);
            assert.equal((await page.locator('#merchandiser-admin-sidebar [aria-current="page"]').textContent()).trim(), label);
            console.log(`PASS desktop click: ${label}`);
        }
        await page.getByRole('link', { name: 'Regional Performance', exact: true }).click();
        await page.waitForURL(url => url.searchParams.get('performance_level') === 'Region');
        await page.getByRole('heading', { name: 'Region Performance', exact: true }).waitFor();
        console.log('PASS regional destination');
        console.log('Desktop dimensions', await page.evaluate(() => ({ width: document.documentElement.scrollWidth, height: document.documentElement.scrollHeight })));
        await page.screenshot({ path: 'storage/framework/client-desktop-qa.png' });
        await page.setViewportSize({ width: 390, height: 844 });
        await page.goto(`${base}/merchandisers/client/dashboard`);
        await page.getByRole('button', { name: 'Toggle navigation menu' }).click();
        await page.getByRole('link', { name: 'User Performance', exact: true }).click();
        await page.waitForURL(url => url.searchParams.get('view') === 'user-performance');
        await page.waitForFunction(() => document.getElementById('merchandiser-admin-sidebar').getBoundingClientRect().right <= 0);
        assert.equal(await page.evaluate(() => document.documentElement.scrollWidth <= window.innerWidth), true);
        await page.screenshot({ path: 'storage/framework/client-mobile-qa.png' });
        console.log('PASS mobile menu and no horizontal overflow');
        console.log(JSON.stringify({ pageErrors: errors }));
        assert.deepEqual(errors, []);
    } finally {
        await browser.close();
    }
})().catch(error => { console.error(error); process.exitCode = 1; });
