const fs = require("fs");
const sharp = require("sharp");
const pngToIco = require("png-to-ico");

const SITE_NAME = "Nome da Empresa"; // nessa linha inserir um nome didático e comercial/mkt do Cliente
const BACKGROUND_COLOR = "#ffffff";

const WEBROOT_DIR = __dirname + "/../../webroot";
const TMP_DIR = __dirname + "/../../tmp";

const BASE_SVG = WEBROOT_DIR + "/src/favicon/icon.svg";
const BASE_PNG = WEBROOT_DIR + "/src/favicon/logo.png";

const OUTPUT_DIR = WEBROOT_DIR;

async function createTempPNG(src, output, width = 32, height = 32, padding = 1, options) {
  return sharp(src)
    .resize({
      width: width - padding * 2,
      height: height - padding * 2,
      fit: "contain",
      background: { r: 255, g: 255, b: 255, alpha: 0 },
      ...options,
    })
    .extend({
      top: padding,
      bottom: padding,
      left: padding,
      right: padding,
      background: { r: 255, g: 255, b: 255, alpha: 0 },
      ...options,
    })
    .png()
    .toFile(TMP_DIR + "/" + output)
    .then(function (info) {
      return TMP_DIR + "/" + output;
    })
    .catch(function (err) {
      console.log(err);
      return null;
    });
}

async function createPNG(src, output, width = 192, height = 192, padding = 20, options) {
  return sharp(src)
    .resize({
      width: width - padding * 2,
      height: height - padding * 2,
      fit: "contain",
      background: BACKGROUND_COLOR,
      ...options,
    })
    .extend({
      top: padding,
      bottom: padding,
      left: padding,
      right: padding,
      background: BACKGROUND_COLOR,
      ...options,
    })
    .flatten({ background: BACKGROUND_COLOR })
    .png()
    .toFile(OUTPUT_DIR + "/" + output)
    .then(function (info) {
      return OUTPUT_DIR + "/" + output;
    })
    .catch(function (err) {
      console.log(err);
      return null;
    });
}

async function generateIcoFile() {
  const tempIcons = [];

  const x = await createTempPNG(BASE_SVG, "tmp-icon16.png", 16, 16);
  if (x) tempIcons.push(x);

  const y = await createTempPNG(BASE_SVG, "tmp-icon32.png", 32, 32);
  if (y) tempIcons.push(y);

  if (tempIcons.length > 0) {
    const buf = await pngToIco(tempIcons);
    fs.writeFileSync(OUTPUT_DIR + "/favicon.ico", buf);

    for (let j = 0; j < tempIcons.length; j++) {
      if (fs.existsSync(tempIcons[j])) {
        fs.unlinkSync(tempIcons[j]);
      }
    }
  }
}

async function generateManifestPNGs() {
  await createPNG(BASE_PNG, "apple-touch-icon.png", 180, 180, 20);
  await createPNG(BASE_PNG, "android-chrome-192x192.png", 192, 192, 20);
  await createPNG(BASE_PNG, "android-chrome-512x512.png", 512, 512, 30);

  let manifest = {
    name: require(__dirname + "/../../package.json").name,
    icons: [],
  };
  if (fs.existsSync(OUTPUT_DIR + "/manifest.webmanifest")) {
    const existing = JSON.parse(fs.readFileSync(OUTPUT_DIR + "/manifest.webmanifest"));
    manifest = { ...manifest, ...existing };
  }
  if (typeof SITE_NAME !== "undefined" && Boolean(SITE_NAME)) {
    manifest.name = SITE_NAME;
  }
  manifest.icons = [
    { src: "/android-chrome-192x192.png", type: "image/png", sizes: "192x192" },
    { src: "/android-chrome-512x512.png", type: "image/png", sizes: "512x512" },
  ];

  fs.writeFileSync(OUTPUT_DIR + "/manifest.webmanifest", JSON.stringify(manifest, null, 2));
}

async function generateSocial() {
  await createPNG(BASE_PNG, "facebook.png", 400, 400, 25);
  await createPNG(BASE_PNG, "facebook_lg.png", 1200, 630, 50);
}

(async function () {
  await generateIcoFile();
  await generateManifestPNGs();
  await generateSocial();

  fs.copyFileSync(BASE_SVG, OUTPUT_DIR + "/icon.svg");

  const suggestedHtml = `
<link rel="icon" href="/favicon.ico" sizes="32x32">
<link rel="icon" href="/icon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">
<link rel="manifest" href="/manifest.webmanifest">
`;

  console.log(suggestedHtml.trim());
})();
