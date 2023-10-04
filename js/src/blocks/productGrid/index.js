/**
 * WordPress dependencies
 */
import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';

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
		const blockProps = useBlockProps.save({
			className: 'woo-product-grid',
		});
		const innerBlocksProps = useInnerBlocksProps.save({ ...blockProps });

		return <div {...innerBlocksProps} />;
	},
};

export { name, settings };
