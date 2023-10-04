/**
 * WordPress dependencies.
 */
import { registerBlockType } from '@wordpress/blocks';

/**
 * Blocks dependencies.
 */
import * as productGrid from './blocks/productGrid';
import * as productGridLayout from './blocks/productGridLayout';

/**
 * Given a block object, register the block and its settings.
 *
 * @param {Object} block Block registration object.
 */
const registerBlock = (block) => {
	if (!block) {
		return;
	}
	const { name, settings } = block;
	registerBlockType(name, settings);
};

/**
 * Loops through all of our custom blocks and registers them.
 */
export const registerBlocks = () => {
	// Custom JS blocks.
	[productGrid, productGridLayout].forEach(registerBlock);
};

// Register custom blocks.
registerBlocks();
