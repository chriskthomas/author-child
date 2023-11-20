const fs = require("fs");
const sass = require("sass");
const fontawesomeSubset = require("fontawesome-subset").fontawesomeSubset;

fontawesomeSubset(
  {
    solid: ["bars", "envelope"],
    brands: ["twitter-square", "linkedin", "github-square", "mastodon"],
  },
  "assets/fonts/"
);

// Generate CSS
const style = sass.compile("sass/style.scss");
fs.writeFile("style.css", style.css, (err) => {
  if (err) {
    console.error(err);
  }
});

const style_min = sass.compile("sass/style.scss", { style: "compressed" });
fs.writeFile("style.min.css", style_min.css, (err) => {
  if (err) {
    console.error(err);
  }
});
