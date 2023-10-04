/**
 * Webpack config.
 */

const path = require('path');
const defaults = require('@wordpress/scripts/config/webpack.config.js');

module.exports = {
	...defaults,
	...{
		entry: {
			index: path.resolve(process.cwd(), 'js/src', 'index.js'),
		},
		output: {
			filename: '[name].js',
			path: path.resolve(process.cwd(), 'js/build'),
		},
	},
};
