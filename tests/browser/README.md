# Local browser regression checks

These checks launch a fresh, isolated Chrome profile against the local preview servers. They do not attach to a personal browser session or point to production. Fixture accounts must exist in the isolated preview databases; the scripts do not create real accounts or send messages.

Set `PLAYWRIGHT_MODULE` to an installed Playwright module and optionally `CHROME_EXECUTABLE`. Set `CMIH_BROWSER_EVIDENCE_DIR` to choose where screenshots and JSON evidence are saved (default `/tmp/cmih-isolated-browser`). Run this folder’s `.cjs` script with Node. The local ports are intentionally fixed to the isolated preview configuration.

Checks cover desktop and 390px layouts, role navigation, runtime/network failures and horizontal overflow. Creator checks exercise reusable drawers and keyboard dismissal. Brands/Merchandiser checks click admin, agency and field navigation and cover supervisor/client views. Earlier feature tests cover writes and permissions separately. Passing these checks does not establish that every possible browser, device, external service or production configuration is error-free.
