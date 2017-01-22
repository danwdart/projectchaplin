module.exports = {
   entry: __dirname+"/public/js/src/index.js",
   output: {
       path: __dirname+'/public/js',
       filename: "index.js"
   },
   module: {
       loaders: [
           { test: /\.js$/, exclude: /node_modules/, loader: "babel-loader" },
           { test: /\.css$/, loader: "style!css" },
           { test: /\.png$/, loader: "url-loader?mimetype=image/png" },
           { test: /\.svg$/, loader: "url-loader?mimetype=image/svg" },
           { test: /\.ttf$/, loader: "url-loader?mimetype=font/ttf" },
           { test: /\.eot$/, loader: "url-loader?mimetype=font/eof" },
           { test: /\.woff$/, loader: "url-loader?mimetype=font/woff" },
           { test: /\.woff2$/, loader: "url-loader?mimetype=font/woff" }
       ]
   }
};
