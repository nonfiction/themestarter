import json from "./block.json";
import * as lib from "@lib";

const { InnerBlocks, RichText, useBlockProps } = wp.blockEditor;

lib.registerBlockType(json, {
  edit: ({ attributes, setAttributes, className }) => {
    const blockProps = useBlockProps({
      className: `${className || ""} theme-aside widget`.trim(),
    });

    return (
      <aside {...blockProps}>
        <RichText
          tagName="span"
          className="sidebar-title"
          value={attributes.heading}
          onChange={(heading) => setAttributes({ heading })}
          placeholder="Aside heading"
        />
        <div className="widget-content">
          <InnerBlocks
            renderAppender={() => <InnerBlocks.ButtonBlockAppender />}
          />
        </div>
      </aside>
    );
  },
  save: ({ attributes }) => (
    <aside className="theme-aside widget">
      {attributes.heading ? (
        <RichText.Content
          tagName="span"
          className="sidebar-title"
          value={attributes.heading}
        />
      ) : null}
      <div className="widget-content">
        <InnerBlocks.Content />
      </div>
    </aside>
  ),
});
