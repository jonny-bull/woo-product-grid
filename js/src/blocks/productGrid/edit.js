/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { Fragment } from '@wordpress/element';
import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';

/**
 * Edit function for 'woo-product-grid/product-grid'.
 *
 * @return {Fragment} A React Component containing our processed data.
 */
const Edit = () => {
	const blockProps = useBlockProps({
		className: 'woo-product-grid',
	});

	const innerBlocksProps = useInnerBlocksProps(
		{ ...blockProps },
		{
			allowedBlocks: [
				'core/heading',
				'woo-product-grid/product-grid-layout',
			],
			orientation: 'horizontal',
			templateLock: 'all',
			template: [
				['core/heading', { placeholder: __('Block title') }],
				['woo-product-grid/product-grid-layout', {}],
			],
		}
	);

	return <div {...innerBlocksProps} />;
};

export default Edit;
