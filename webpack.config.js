module.exports = {
   entry: __dirname+"/public/js/src/index.js",
   output: {
       path: __dirname+'/public/js',
       filename: "index.js"
   },
   module: {
       loaders: [
           { test: /\.js$/, exclude: /node_modules/, loader: "babel-loader" },
           { test: /\.css$/, loader: "style!css" }
       ]
   }
};
