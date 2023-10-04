/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	// eslint-disable-next-line @wordpress/no-unsafe-wp-apis
	__experimentalNumberControl as NumberControl,
	SelectControl,
} from '@wordpress/components';
import { Fragment } from '@wordpress/element';
import ServerSideRender from '@wordpress/server-side-render';

/**
 * Internal dependencies
 */
import metadata from './block.json';

/**
 * Edit function for 'woo-product-grid/product-grid-layout'.
 *
 * @param {Object}   props               Props passed to the function.
 * @param {?Object}  props.attributes    Attributes and their values.
 * @param {Function} props.setAttributes Function used to set values for changed attributes.
 * @return {Fragment} A React Component containing our processed data.
 */
const Edit = ({ attributes, setAttributes }) => {
	const {
		productOffset,
		productsToShow,
		productsPerRow,
		productOrder,
		productOrderDirection,
	} = attributes;

	return (
		<>
			<InspectorControls>
				<PanelBody title={__('Product grid settings')}>
					<NumberControl
						label={__('Products to show')}
						onChange={(newValue) => {
							setAttributes({
								productsToShow: newValue,
							});
						}}
						max={36}
						min={1}
						value={productsToShow}
					/>
					<NumberControl
						label={__('Products per row')}
						onChange={(newValue) => {
							setAttributes({
								productsPerRow: newValue,
							});
						}}
						max={4}
						min={1}
						value={productsPerRow}
					/>
					<NumberControl
						label={__('Product offset')}
						onChange={(newValue) => {
							setAttributes({
								productOffset: newValue,
							});
						}}
						max={36}
						min={0}
						value={productOffset}
					/>
					<SelectControl
						label={__('Sort by', 'ennismore')}
						value={productOrder}
						options={[
							{
								label: __('Date'),
								value: 'date',
							},
							{
								label: __('Date modified'),
								value: 'modified',
							},
							{
								label: __('ID'),
								value: 'id',
							},
							{
								label: __('Product name'),
								value: 'name',
							},
							{
								label: __('Product type'),
								value: 'type',
							},
							{
								label: __('Random'),
								value: 'rand',
							},
						]}
						onChange={(value) => {
							setAttributes({ productOrder: value });
						}}
					/>
					<SelectControl
						label={__('Sort order', 'ennismore')}
						value={productOrderDirection}
						options={[
							{
								label: __('Ascending'),
								value: 'ASC',
							},
							{
								label: __('Descending'),
								value: 'DESC',
							},
						]}
						onChange={(value) => {
							setAttributes({ productOrderDirection: value });
						}}
					/>
				</PanelBody>
			</InspectorControls>
			<ServerSideRender block={metadata.name} attributes={attributes} />
		</>
	);
};

export default Edit;
