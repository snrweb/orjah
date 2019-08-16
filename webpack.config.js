const path = require("path");
const HtmlWebpackPlugin = require("html-webpack-plugin");

const view = "storeView";
const finalBundle = "store_bundle.js";

//const view = "storeAdmin";
//const finalBundle = "storeAdmin_bundle.js";

// const view = "details";
// const finalBundle = "details_bundle.js";

//const view = "admin";
//const finalBundle = "admin_bundle.js";

module.exports = {
  entry: ["whatwg-fetch", `./public/js/${view}/index.js`],
  //entry: ["whatwg-fetch", `./public/js/admin/admin.js`],
  output: {
    path: path.join(__dirname, "public/js/dist"),
    filename: finalBundle,
    publicPath: "/"
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: "babel-loader"
        }
      },
      {
        test: /\.svg$/,
        loader: "svg-react-loader"
      },
      {
        test: /\.(png|jpg|gif)$/,
        use: [
          {
            loader: "file-loader",
            options: {}
          }
        ]
      }
    ]
  },
  plugins: [
    new HtmlWebpackPlugin({
      template: "./index.html"
    })
  ]
};
