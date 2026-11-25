# Screenshots

The module screenshots are the `resources/screen_*.png` files listed under `screenshots` in `module.json`. The
marketplace shows them on the module page, so they are often the first thing a user sees of the module. Retake
them when the module's UI changes (see [When to retake the screenshots](BROWSER-TESTS.md#when-to-retake-the-screenshots)
in BROWSER-TESTS.md).

## Plan

- Install the module and try all its features.
- Choose the features that matter most.
- Use **at most 8 screenshots**.
- Show what is specific to your module. For this theme: the single top bar on a large screen, the bottom bar on a
  phone, the left navigation, dark mode (Dark Mode module) and the configuration page.
- Skip steps that look the same in every module, such as standard context menus.

## Prepare content

- Create realistic sample data. For a theme, for example, a space with a banner image, a profile picture and a
  description, and a stream with posts by several users.
- Make the stream entries look active: add comments and likes from several accounts.
- No test data on the screenshots: no `QA ` or `[TEST]` names, no `Test User 1` accounts, no lorem ipsum.

## Capture

- Set the browser viewport to **1280 × 910 pixels**.
- For longer pages, such as a long wiki page, increase the height and keep the same width.
- For mobile views (the bottom bar), set the browser viewport to **400 × 900 pixels**.
- Capture the module's own actions step by step.
- Capture **1:1 pixel**: one pixel of the PNG = one CSS pixel of the viewport, so a 1280 px wide viewport gives a
  1280 px wide PNG. On a high-density screen (Retina, 4K with scaling), set the device pixel ratio to 1 in the
  browser's responsive mode instead of downscaling the image afterwards. Do not use a "fit to size" or
  low-quality option of the capture tool.

## Publish

- Check the PNG width: **1280 pixels** (400 for mobile views). If it is not, retake the screenshot at the right
  viewport and pixel ratio rather than resizing it.
- Save as PNG without lossy compression: no JPEG, no color reduction (pngquant, "8-bit" or "palette" export), no
  quality slider. PNG compression levels (up to 9) are lossless and fine.
- Save them as `resources/screen_<n>.png`, in the order they should appear, and keep the `screenshots` list of
  `module.json` in sync (add or remove entries when their number changes).
- Check them once more for clarity, relevance and quality, and update them when the UI of your module changes
  (`MG-LIST-04`).

## Retaking them with Claude Code and Claude in Chrome

Set up Claude Code and Claude in Chrome as described in
[BROWSER-TESTS.md](BROWSER-TESTS.md#running-the-tests-with-claude-code-and-claude-in-chrome), then paste this
prompt:

```text
Retake the module screenshots by driving Claude in Chrome on the HumHub instance open in Chrome, following
docs/SCREENSHOTS.md.

Before starting, ask me whether all the screenshots listed in module.json should be retaken, or which ones, and
wait for my answer. Then:

1. Tell me what each screenshot will show (the current one if it is retaken, or a new feature) and which sample
   data it needs, and wait for my go.
2. Have Claude in Chrome create the sample data with the test accounts of docs/BROWSER-TESTS.md, following the
   "Prepare content" section, then take the screenshots following the "Capture" section.
3. Check them as described in "Publish" (1:1 pixel, 1280 px wide, lossless PNG), replace the files in resources/
   and update module.json if their number changed. Do not commit anything.
4. At the end, list the screenshots replaced or added, and the sample data created, and ask whether to delete it.
```
