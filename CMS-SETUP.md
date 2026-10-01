# Pages CMS

1. Commit `.pages.yml` and `src/content/media/*.json` to the GitHub repository.
2. Connect the repository in https://pagescms.org/.
3. News articles are also the agenda source: fill in `event` only when a date is known.
4. Existing album photos are still remote GitHub URLs. New CMS uploads go to `public/images`; migrate existing images locally before relying on the image picker to manage them.
5. Changes require a deployment/build of the Astro site. Check the Pages CMS editor against your installed version before handing off to clients.
