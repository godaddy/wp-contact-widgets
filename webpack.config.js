const path = require( 'path' );
const webpack = require( 'webpack' );

module.exports = {
  entry: {
    './assets/js/contact-widget-blocks' : './includes/blocks/blocks.js',
  },
  output: {
    path: path.resolve( __dirname ),
    filename: '[name].js',
  },
  watch: false,
  devtool: 'cheap-eval-source-map',
	module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /(node_modules)/,
        use: {
          loader: 'babel-loader',
        },
      },
    ],
  },
  plugins: [],
};
