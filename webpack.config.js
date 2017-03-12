module.exports = {
   context: __dirname+'/public/js/',
   entry: './src/index.js',
   output: {
       path: './public/js/',
       publicPath: '/public/js/',
       filename: "index.js"
   },
   module: {
       loaders: [
           { test: /\.js$/, exclude: /node_modules/, loader: "babel-loader" },
           { test: /\.css$/, loader: "style-loader!css-loader" },
           { test: /\.png/, loader: "url-loader?mimetype=image/png" },
           { test: /\.woff2?/, loader: "url-loader?limit=10000&mimetype=application/font-woff" },
           { test: /\.ttf/, loader: "file-loader" },
           { test: /\.eot/, loader: "file-loader" },
           { test: /\.svg/, loader: "file-loader" },
       ]
   }
};
