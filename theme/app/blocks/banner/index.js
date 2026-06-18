import json from "./block.json";
import * as lib from "@lib";

const { Button } = wp.components;
const {
  BlockControls,
  MediaUpload,
  MediaUploadCheck,
  RichText,
  useBlockProps,
} = wp.blockEditor;

lib.registerBlockType(json, {
  edit: ({ attributes, setAttributes, className }) => {
    const backgroundStyle = attributes.background_url
      ? { backgroundImage: `url(${attributes.background_url})` }
      : undefined;
    const blockProps = useBlockProps({
      className: `${className || ""} banner-block editor`.trim(),
    });

    return (
      <div {...blockProps}>
        <div className="intro-section">
          <div className="bg-stretch" style={backgroundStyle} />
          <div className="container">
            <div className="intro-text">
              <RichText
                tagName="strong"
                className="intro-title"
                value={attributes.heading}
                onChange={(heading) => setAttributes({ heading })}
                placeholder="Add banner heading"
              />
              <RichText
                tagName="div"
                className="intro-copy"
                value={attributes.content}
                onChange={(content) => setAttributes({ content })}
                placeholder="Optional supporting text"
              />
            </div>
          </div>
        </div>

        <BlockControls>
          <MediaUploadCheck>
            <MediaUpload
              allowedTypes={["image"]}
              value={attributes.background_id}
              onSelect={(media) =>
                setAttributes({
                  background_id: media.id,
                  background_url: media.url,
                })
              }
              render={({ open }) => (
                <Button onClick={open} icon="format-image">
                  Banner image
                </Button>
              )}
            />
          </MediaUploadCheck>
        </BlockControls>
      </div>
    );
  },
  save: () => null,
});
