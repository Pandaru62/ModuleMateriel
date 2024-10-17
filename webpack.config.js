const path = require('path');

module.exports = {
  mode: 'development', // Change to 'production' for production
  entry: './src/index.js', // Make sure this path exists or update it
  output: {
    filename: 'bundle.js',
    path: path.resolve(__dirname, 'dist'),
  },
};
