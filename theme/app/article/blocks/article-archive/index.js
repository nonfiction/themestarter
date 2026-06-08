import json from './block.json';
import { registerBlockType } from '@nf';

const { serverSideRender: ServerSideRender } = wp;

registerBlockType(json, {
  edit: ({ attributes, className }) => (
    <div className={`${className || ''} editor`.trim()}>
      <ServerSideRender block="nf/article-archive" attributes={attributes} />
    </div>
  ),
  save: () => null,
});
