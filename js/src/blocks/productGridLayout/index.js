/**
 * Internal dependencies
 */
import json from './block.json';
import Edit from './edit';

const { name } = json;
const settings = {
	...json,
	edit: Edit,
	save: () => {
		return null;
	},
};

export { name, settings };
