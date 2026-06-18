import json from "./block.json";
import * as lib from "@lib";

const { InnerBlocks, useBlockProps } = wp.blockEditor;

lib.registerBlockType(json, {
  edit: ({ className }) => {
    const blockProps = useBlockProps({
      className: `${className || ""} content-accordion`.trim(),
    });

    return (
      <ul {...blockProps}>
        <InnerBlocks
          allowedBlocks={["nf/accordion-item"]}
          template={[["nf/accordion-item"]]}
          templateLock={false}
          renderAppender={() => <InnerBlocks.ButtonBlockAppender />}
        />
      </ul>
    );
  },
  save: () => <InnerBlocks.Content />,
});
