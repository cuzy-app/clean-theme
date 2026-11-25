# Browser tests

The Codeception suite (`tests/`, run with `php <humhub>/protected/vendor/bin/codecept run unit` from `tests/`)
checks the PHP side: the configuration (saving, default values, generated CSS variables, JSON import and export),
the Google Fonts downloaded to the server (key, publication, fallback to Google), the CSS variables stylesheet
(served as CSS, never intercepted by a user gate), the theme detection and the guard against disabling the module
while Clean Theme Pro is enabled. Do not repeat these checks here. The unit tests cannot check what happens in the
browser: the theme overrides core views (the main layout and its top bar, the left navigation, the account pages)
and its JavaScript and SCSS depend on the core markup. This file is a prompt for an AI agent that drives a browser,
covering only the parts most likely to break on a core upgrade. Run it on a test instance **after every HumHub
core upgrade**, after an upgrade of a module this one depends on, and before a release that changes JS, CSS or
views.

When the tests pass after a change of the module's UI, the module screenshots may need to be retaken: see
[When to retake the screenshots](#when-to-retake-the-screenshots).

Duration: about 35 minutes for the full run; about 15 minutes for the **quick run**, which is made of the tests
marked `[quick]` and is enough after a core upgrade.

## Running the tests with Claude Code and Claude in Chrome

Claude Code can drive the browser itself through the Claude in Chrome extension, so nobody has to click or forward
screenshots by hand:

1. Open the testing HumHub instance in Chrome, with the Claude in Chrome extension enabled, logged in with an
   administrator account. In the extension, select a **Claude Sonnet** model.
2. Enable the Chrome integration in Claude Code: type `/chrome` in the running session if the extension is enabled
   by default, otherwise exit the session and restart it with `claude --chrome --resume` (this resumes the same
   conversation).
3. Ask Claude Code to run the browser tests of this file. It reads the instructions below, then opens tabs,
   clicks, fills in forms, reads the console errors and takes screenshots. It sees the screenshots itself, so you
   do not need to forward them. It writes its findings to `browser-tests-report.md`.

Prompt to copy and paste into Claude Code:

```text
Run the browser tests of docs/BROWSER-TESTS.md on the HumHub instance open in Chrome, by driving
Claude in Chrome (with a Claude Sonnet model).

Before starting, ask me this question and wait for my answer: should the full tests be run (e.g. after a major
core upgrade), or only the tests where something might have broken since a previous version? In that case, since
which version? Then analyze `docs/CHANGELOG.md` and the commits since that version (`git log v<version>..HEAD`),
tell me which tests you will run and why, and run only those (plus the setup and the `[quick]` tests).

Then:

1. Read the file and give Claude in Chrome its "Instructions for the browser agent" section, then let it run the
   tests. Relay its stop-and-warn messages to me.
2. When it reports issues, fix them in the module code (minimal changes, the module's existing code style). Do not
   commit anything.
3. If a fix needs the cache to be flushed or a migration to be run, ask Claude in Chrome to do it from the
   administration (for example Administration > Settings > "Flush cache" button, and
   Administration > Information > Database to run the migrations).
4. Ask Claude in Chrome to retest the tests that failed, plus the ones the fix may have affected, and check that
   each issue is really fixed. Fix what is still broken, or what the fix broke.
5. Repeat steps 2 to 4 until every issue is fixed. After 3 retests with issues still open, stop and ask me
   whether to continue.
6. At the end, give me the list of the issues found and fixed (with the files changed), the issues left open, and
   the steps skipped because they need a human. If a fix or a change since the last release changed what the
   module screenshots show, tell me which ones should be retaken (see docs/SCREENSHOTS.md).
```

Some steps still need a human (for example a native browser dialog): the agent asks, or skips them and lists them
as skipped in the report.

## When to retake the screenshots

The module screenshots (`resources/screen_*.png`, listed in `module.json`) are shown in the marketplace. Retake
the ones concerned, once the browser tests pass, when:

- the module's UI changed: a new feature, a moved or renamed menu entry, button or field, a new layout;
- a HumHub core or theme upgrade changed the look of the module's pages;
- a screenshot no longer matches the current texts or translations.

How to plan, prepare and take them: [SCREENSHOTS.md](SCREENSHOTS.md).

---

## Instructions for the browser agent

You are checking, in a real browser, that the "Clean Theme" module of HumHub works correctly: display (CSS),
JavaScript behavior and responsive layout of the top bar, the bottom bar, the left navigation and the account
pages. You report problems for the developer (another Claude) who will fix them: you do **not** fix anything, and
you do not change the module or HumHub settings unless a test asks for it.

### Parameters

- `HUMHUB` = the HumHub test instance currently open in the browser. You should already be logged in there with an administrator account.
- Test users. Each constant is a test user, identified by its email.
  - `ADMIN` = `cuzyapp@proton.me`, member of the "Administrator" group: configuration (T8) and administration
    pages
  - `TEST1` = `test1.oven017@passinbox.com`, standard user, member of at least one space with posts (all the other
    tests)
- The Clean Theme module is enabled and the "Clean" theme is the active theme (Administration > Settings >
  Appearance). If another theme is active (for example "CleanPro" of the Clean Theme Pro module), stop and ask
  before changing it.
- The Mail module is installed, for the messages icon of the top bar. If not, skip the steps about messages.
- Dark mode = the Dark Mode module (moon icon of the top bar, or Account settings > Dark Mode), if installed.

Other mail aliases (`test<n>.…@passinbox.com`, not defined as a test user above) are free: use them when a test
needs an email address that is not registered yet.

### Setup: test accounts

Before running the tests:

1. Open `HUMHUB/admin/user/list` and check that you are logged in as an administrator (the page opens). If not,
   stop and ask.
2. For `ADMIN` and each `TESTn` defined in the parameters, search the email in the list
   (`HUMHUB/admin/user/list?UserSearch%5BfreeText%5D=<email>`, URL-encode the `@` as `%40`).
   - Found and enabled: note its ID and username; for `ADMIN`, check it is in the "Administrator" group. Then set
     a new random strong password (at least 16 characters, letters, digits and symbols) in
     `HUMHUB/admin/user/edit?id=<ID>` → "Password" tab, save, and note it. Skip this for the account you are
     currently logged in with.
   - Found but disabled or unapproved: note it and ask before changing it.
   - Not found: create it in `HUMHUB/admin/user/add`:
     - Username: `test-admin` for `ADMIN`, `test-user-<n>` for `TESTn`
     - Email: the account's email
     - Password: a random strong password (at least 16 characters, letters, digits and symbols) that you note
     - First name / Last name: `Test` / `Admin`, or `Test` / `User <n>`
     - Group: "Administrator" for `ADMIN`, the default group otherwise
     - Save, then check the user appears in the list as enabled. Note its ID and that **you created it**.
3. Write down a table of the accounts (role, ID, username, email, created by you yes/no), **without the
   passwords**: keep them only for yourself during the run. It goes in the report.
4. Open Administration > Modules > Clean Theme > Configure and note the options as they are: the tests change some
   of them, restore them at the end.

To switch account, log out (user menu, top right → "Logout"), then log in with the other account and the
password you set. Do the tests grouped by account to limit the switches, and log back in as `ADMIN` at the end.
Do not use the "Impersonate" action: its confirmation is a native browser dialog that needs a human click.

Legal pages: you are expressly authorized to accept all legal pages and consents HumHub or the module shows
(Terms and Conditions, Privacy Policy, cookie banner, "I agree" checkboxes…), for every test account, so they
do not block the tests.

### Rules

- Keep DevTools open (Console + Network, "Preserve log"), and look at the console on **every** page. Any
  JavaScript error or failed request (4xx/5xx) on a HumHub page is an issue, even if the page looks fine. Ignore
  the noise that is not about this module: warnings from browser extensions, third-party scripts and fonts
  blocked by your own browser, and `favicon.ico` 404s.
- A narrow-width check that loads a page in a temporary same-origin frame ends that page's Mercure live connection
  when the frame is removed, and HumHub then logs `humhub.modules.live.mercure: Mercure error` once per frame
  removed. These are not findings: report a Mercure error only when it appears on a page loaded normally, without
  any frame. Do not probe `/.well-known/mercure` by hand: a request without parameters always answers 400
  `missing "match" subscription parameter`, which says nothing about the site.
- Check each screen at 1440 px and 390 px wide (responsive mode), unless the test says otherwise. Below 576 px the
  theme turns the main menu of the top bar into a bottom bar, so the 390 px checks matter as much as the desktop
  ones. These exact widths are not always possible (the screen is smaller than the window you need, or the browser
  cannot be resized or emulated): when one is not possible, use the closest width you can get (as wide as possible
  for 1440, as narrow as possible for 390, and always below 576) and write the real width in the report.
- Check light and dark mode (Dark Mode module) where a test says so.
- Log out for the logged-out tests (or use a private window).
- Only create test data (contents, spaces, comments…) with the test accounts, and prefix any title or text you
  type with `[TEST]` so it is easy to find and delete afterwards.
- **Settings changed:** every time a test changes a module or HumHub setting, add it to a list (setting, original
  value, new value). The cleanup restores them.
- **Screenshots:** for every page (or screen state) where you find an issue, take a screenshot at the viewport
  where it appears (both 1440 and 390 if it appears on both), as soon as you see it and before changing page. Add
  a close-up of the faulty element when the issue is small. The screenshots are not saved as files: label each one
  `BT-<nn>-<viewport>` (e.g. `BT-03-390`) and describe in the report precisely what it shows. No screenshot is
  needed for the tests that pass.
  Screenshots must be **1:1 pixel**, at the screen's native resolution: do not resize, scale down or compress
  them, and do not use a "fit to size" or low-quality option of your tool. If your tool still reduces the image
  (the text of the page is blurry or smaller than on the screen), capture only the faulty area at its real size
  (the element, or the visible part of the page scrolled to it, or zoom on a region) instead of the whole page.
- **Do not get stuck:** when a step needs something you cannot do (a CAPTCHA, a native browser dialog, an API key
  or a file you do not have, a phone, the command line), do not retry in a loop and do not work around it by
  entering credentials yourself. Skip the step, say why in the report (it counts as skipped) and, if the rest of
  the test depends on it, ask the user.
- **Stop and warn:** stop the tests, without running the next ones, and warn the user in the chat first (what
  blocks, where, with the evidence), then ask what to do, when:
  - you find a bug that would affect the rest of the tests (for example the login or the dashboard gives an error
    page, a JavaScript error breaks every page, the top bar is missing);
  - a configuration does not allow to run the tests correctly (the "Clean" theme is not the active theme, a
    required module disabled, a prerequisite you cannot create).
  Any other failure does not stop the run: note it and continue with the next test.
- If a step fails (and it is not a reason to stop), note it and continue with the next test.

### Tests

**T1. Top bar on large screens** [quick] — as `TEST1`, the dashboard, at 1440 px
- Display: a single top bar holds, from left to right, the site logo or name, the space chooser ("My spaces" or
  the current space) and the main menu items (icon above the text), then the search icon, the notifications
  bell, the messages envelope (Mail module) and the account avatar. Nothing wraps to a second row, nothing
  overlaps, the bar stays fixed at the top when scrolling.
- Interaction: open each of them in turn: the space chooser lists the spaces and filters them as you type; the
  search opens its dropdown and a search shows results; the notifications and messages dropdowns open and list
  their entries; the account menu shows "My Profile", "Settings" and "Logout" (and "Administration" as `ADMIN`).
  Each dropdown is fully visible inside the window and closes on a click outside.
- Display: the item of the current page is highlighted in the main menu (open another menu item: the highlight
  follows).

**T2. Top menu overflow** — as `TEST1`, any page, from 1440 px down to 600 px
- Interaction: narrow the window step by step: the main menu items that no longer fit move into a "Menu"
  dropdown (lines icon) at the end of the menu instead of wrapping to a second row; widen it again: they come back
  in the same order and the "Menu" dropdown disappears when everything fits. Every item of the "Menu" dropdown
  opens its page.
- Display: reload the page at about 800 px wide: the top bar does not show a second row, even for a moment, while
  the page loads.
- Display: as `ADMIN`, with a very long site name (Administration > Settings > General > "Name of the
  application", 60 characters; restore it afterwards), the name is cut with an ellipsis and the right-hand icons
  stay visible at 1440 px and 390 px.

**T3. Bottom bar on small screens** [quick] — as `TEST1`, the dashboard and a space, at 390 px
- Display: the top bar keeps only the logo, search, notifications, messages and avatar; the main menu becomes a
  bar fixed at the bottom of the screen with an icon and a short text per item, and the active item is
  highlighted. The end of the page (last stream entry, "load more" button) is not hidden under the bottom bar.
- Interaction: the space chooser of the bottom bar opens **upwards**, entirely visible, with its search field at
  the bottom, next to the bar, and the "Create Space" button at the top; typing filters the list; the "Menu" dropdown of the bottom bar, if any, opens upwards too. The notifications and
  messages dropdowns open below the top bar, centered and fully visible.
- Interaction: rotate to landscape (or widen to 700 px), then back to 390 px: the bar switches between top and
  bottom each time without a reload, and the dropdowns open in the right direction.

**T4. Menus on small screens options** — as `ADMIN` for the configuration, as `TEST1` for the checks, at 390 px
- Data: in Administration > Modules > Clean Theme > Configure > "Menus on small screens", check "Hide the top menu
  on scroll down" and "Hide the bottom menu on scroll down", save.
- Interaction: on a long stream, scroll down: the top bar and the bottom bar slide out of view; scroll up a little:
  both come back. At the very top of the page the bars are visible; reaching the bottom of the page does not make
  it jump or freeze. Above 576 px wide (and at 1440 px) the bars never hide.
- Display: check "Hide the text of the bottom menu buttons", save: the bottom bar shows icons only, still
  evenly spread; the top bar at 1440 px is unchanged. Restore the three options.

**T5. Active icons and PJAX navigation** [quick] — as `TEST1`, at 1440 px
- Display: open the content search page (search icon, type a word, then "Show all results" or "Advanced Content
  Search" under the contents, `HUMHUB/content/search`), the notifications overview ("Show all notifications" in
  the notifications dropdown) and the messages page (Mail module): on each of them the matching icon of the top
  bar is highlighted, and only that one.
- Interaction: from there, click a main menu item (the page changes without a full reload): the icon is no longer
  highlighted, and the menu item is. Navigate this way through five pages, then resize the window: the overflow
  of T2 still works and the console has no error.

**T6. Left navigation** — as `ADMIN`, at 1440 px, Account settings (account menu > "Settings"), a space with
several modules, and Administration > Users
- Interaction: on a page longer than the window, scroll down: the left menu panel stays fixed just below the top
  bar, with the same width, and never covers the top bar or the content; scroll back up: it returns to its place.
  A left menu taller than the window simply scrolls with the page.
- Display: the active item has the style of the "Menu style" option (see T8); at 390 px the menu is a horizontal
  row above the content.
- Interaction: only if the instance sets `collapsibleLeftNavigation` for the module in its configuration file (it
  cannot be changed from the browser, human only): the chevron of the menu heading collapses the menu and the
  content takes the whole width; the bars button brings it back.

**T7. Account pages** [quick] — as `TEST1`, account menu > "Settings"
- Display: the profile header of the user (banner, picture, name) is shown above the settings, at 1440 px and
  390 px, and its button reads "My Profile" (not "Edit account").
- Interaction: the button opens the user's own profile. Back on the settings, the tabs and the left menu work and
  a saved change shows the success message.

**T8. Configuration applied** [quick] — as `ADMIN`, Administration > Modules > Clean Theme > Configure
- Data: change "Main color for links" and, under "Top menu", the "Background color", and set "Menu style" to
  "Soft highlight background with primary color text for active items"; save. The success message appears.
- Display: in the Network panel, the stylesheet `clean-theme-config-css?v=…` (or `r=clean-theme%2Fcss%2Findex&v=…`
  without pretty URLs) is loaded with status 200 and type `text/css` on every page, and its `v` parameter changed
  after the save. After a reload, the links, the top bar background and the
  active items of the left menu, the top menu and the bottom bar (at 390 px) show the new values; try the two
  other menu styles the same way.
- Display: in dark mode, the dark variants ("Dark mode" fields under each color) are used and every text of the
  top bar, bottom bar and left menu stays readable.
- Display: set the "Font family" of "Base fonts" to `Roboto`, save: the pages use Roboto. If the save showed the
  warning that the fonts could not be downloaded, they load from `fonts.googleapis.com`; otherwise no request goes
  to Google and the font files load from `HUMHUB/assets/clean-theme-fonts/…`.
- Data: restore the original values (noted in the setup), or use "Reset everything to default values" only if
  they were the defaults.

**T9. Logged-out visitor** — logged out: a space visible to guests (only if "Allow visitors limited access to
content without an account" is checked in Administration > Users > Settings), then the login page
- Display: on the public space, the top bar shows the logo, the main menu and the "Sign in" (or "Sign in / up")
  button; at 390 px the search icon is hidden so that the button fits, and the bottom bar works as in T3.
- Interaction: the button opens the login form in a modal; logging in as `TEST1` lands on the page with the full
  top bar.
- Display: the login page (`HUMHUB/user/auth/login`, a core layout without top bar) uses the configured colors
  and fonts (the `clean-theme-config-css` stylesheet is loaded), at 1440 px and 390 px, with no console error.

**T10. Page coverage** — as `TEST1` and `ADMIN`, in light and dark mode, at 1440 px and 390 px
- Display: the dashboard, a space stream, a user profile, Account settings, the Administration, Messages, and a
  space with the Calendar or Wiki module (those that are installed): nothing overflows, overlaps or is unreadable,
  the top bar and the bottom bar stay in place, and the console has no error on any of them.
- Display: with the profile language set to French (Account settings > Settings, restore it afterwards), the
  longer texts of the main menu and of the bottom bar are cut cleanly, without breaking either bar.

### Cleanup

Once all tests are finished and the report is written:

1. List the test data you created (contents, comments, spaces…), the settings you changed (from the "Settings
   changed" list) and the test accounts **you created** during the setup (never the ones that already existed).
2. Restore the changed settings to their original values, and say which ones you could not restore.
3. Ask whether the test data and the accounts you created should be deleted. Do not delete anything without a
   "yes".
4. If yes: delete the test data, then delete each account you created in `HUMHUB/admin/user/list` → gear button →
   "Delete" (choose to also delete the contents of the user). Check they no longer appear in the list.

### Report

Produce one file, `browser-tests-report.md`, and nothing else (if you cannot write files, give the report in the
chat). **Never write a password in it**: if a test account needs one later, the developer resets it.

1. Summary: whether the run was stopped early (and why, with the warning), instance, HumHub version (Administration > Information), module version, date, browser, tests run /
   skipped (and why), and the table below.

   | Test | Result (passed / issues / skipped) | Issues |
   |------|------------------------------------|--------|
   | T1   | issues                             | BT-01, BT-03 |

2. The test accounts table from the setup (no passwords).
3. One section per problem, most severe first:

```
### BT-01 — <one-line title>
- Severity: blocker | major | minor | cosmetic | question
- Test: T1
- URL: <exact URL>
- Viewport: 1440 | 390 | both (the real width in px when it is not one of these)
- Theme mode: light | dark | both
- Logged in as: guest | ADMIN | TEST1 | …
- Steps to reproduce: 1. … 2. …
- Expected / Actual: …
- Evidence: screenshot label and what it shows (see "Screenshots" rule); console errors (exact text); failing requests (method, URL, status);
  outerHTML of the faulty element and its relevant computed styles for a visual problem
```

   Severity: **blocker** = the feature cannot be used, data is lost or exposed, or a PHP/JavaScript error page;
   **major** = the feature works but a main step is broken or very hard to use; **minor** = a secondary
   problem with a workaround; **cosmetic** = display only, the feature works; **question** = unclear whether it is
   a problem, the developer decides.

4. The list of the tests that passed, one line each.
5. Skipped steps (`human only` or blocked) and why.
6. Cleanup: the settings restored, what was deleted, what was kept.
