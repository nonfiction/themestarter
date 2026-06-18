import json from "./block.json";
import * as lib from "@lib";

const { serverSideRender: ServerSideRender } = wp;

lib.registerBlockType(json, {
  edit: ({ attributes, className }) => (
    <div className={`${className || ""} editor`.trim()}>
      <ServerSideRender block="nf/article-archive" attributes={attributes} />
    </div>
  ),
  save: () => null,
});
