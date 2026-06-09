import json from "./block.json";
import { registerBlockType } from "@nf";

const { PanelBody, ToggleControl } = wp.components;
const { InspectorControls, InnerBlocks, RichText, useBlockProps } =
  wp.blockEditor;

const ALLOWED_BLOCKS = [
  "core/paragraph",
  "core/heading",
  "core/list",
  "core/image",
  "core/buttons",
  "core/file",
  "core/table",
  "core/separator",
  "core/spacer",
  "core/embed",
];

registerBlockType(json, {
  edit: ({ attributes, setAttributes, className }) => {
    const itemClassName = `${className || ""}${attributes.open ? " slide-open" : ""}`;
    const blockProps = useBlockProps({
      className: itemClassName.trim(),
    });

    return (
      <li {...blockProps}>
        <RichText
          tagName="h3"
          className="accordion-opener"
          value={attributes.title}
          onChange={(title) => setAttributes({ title })}
          placeholder="Accordion title"
        />
        <div className="accordion-slide js-acc-hidden">
          <div className="slide-inner">
            <InnerBlocks
              allowedBlocks={ALLOWED_BLOCKS}
              template={[
                ["core/paragraph", { placeholder: "Accordion content" }],
              ]}
              templateLock={false}
              renderAppender={() => <InnerBlocks.ButtonBlockAppender />}
            />
          </div>
        </div>

        <InspectorControls>
          <PanelBody title="Accordion item settings">
            <ToggleControl
              label="Open by default"
              checked={!!attributes.open}
              onChange={(open) => setAttributes({ open })}
            />
          </PanelBody>
        </InspectorControls>
      </li>
    );
  },
  save: () => <InnerBlocks.Content />,
});
