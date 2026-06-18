import json from "./block.json";
import classnames from "classnames";
import * as lib from "@lib";

const { registerBlockStyle } = wp.blocks;
const { PanelBody, SelectControl, ToolbarGroup } = wp.components;
const { BlockControls, InnerBlocks, InspectorControls, useBlockProps } =
  wp.blockEditor;

registerBlockStyle(json.name, [
  {
    name: "default",
    label: "Default",
    isDefault: true,
  },
  {
    name: "mixed",
    label: "Mixed",
  },
]);

lib.registerBlockType(json, {
  edit: ({ attributes, className, setAttributes }) => {
    let { columns, width } = attributes;
    width = width || "narrow";
    const classes = classnames(
      className,
      "editor",
      "wp-block-nf-grid",
      "items-grid",
      `is-${width}`,
    );
    const blockProps = useBlockProps({
      className: classes,
      "data-columns": columns,
    });

    const options = [
      { label: "Two", value: "2" },
      { label: "Three", value: "3" },
      { label: "Four", value: "4" },
    ];

    const ColumnsChooser = () => {
      function createControl(option) {
        return {
          icon: "screenoptions",
          title: `${option.label} Columns`,
          isActive: columns === option.value,
          onClick: () => setAttributes({ columns: option.value }),
        };
      }

      return <ToolbarGroup controls={options.map(createControl)} />;
    };

    return (
      <>
        <InspectorControls>
          <PanelBody title="Layout Settings">
            <SelectControl
              label="Number of Columns"
              value={columns}
              options={options}
              __next40pxDefaultSize={true}
              onChange={(columns) => setAttributes({ columns })}
            />
          </PanelBody>
        </InspectorControls>

        <BlockControls>
          <ColumnsChooser />
        </BlockControls>

        <div {...blockProps}>
          <InnerBlocks
            templateLock={false}
            renderAppender={() => <InnerBlocks.ButtonBlockAppender />}
          />
        </div>
      </>
    );
  },

  save: ({ attributes, className }) => {
    const { columns } = attributes;
    const classes = classnames(className, "wp-block-nf-grid", "items-grid");

    return (
      <div className={classes} data-columns={columns}>
        <InnerBlocks.Content />
      </div>
    );
  },
});
