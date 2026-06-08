import json from './block.json';
import './editor.css';
import { registerBlockType } from '@nf';

const { PanelBody, TextControl, ToolbarButton } = wp.components;
const { BlockControls, InspectorControls, MediaUpload, MediaUploadCheck, RichText, useBlockProps } = wp.blockEditor;

registerBlockType(json, {
  edit: ({ attributes, setAttributes, className }) => {
    const imageStyle = attributes.imageUrl ? { backgroundImage: `url(${attributes.imageUrl})` } : undefined;
    const blockProps = useBlockProps({
      className: `${className || ''} grid-item editor card-editor`.trim(),
    });

    return (
      <div {...blockProps}>
        <div className={`grid-col has-content ${attributes.imageUrl ? '' : 'no-image'}`}>
          <div className="grid-item">
            {attributes.imageUrl && <div className="bg-stretch" style={imageStyle} />}
            <div className="item-content">
              <RichText
                tagName="h2"
                className="item-title"
                value={attributes.title}
                onChange={(title) => setAttributes({ title })}
                placeholder="Card title"
              />
            </div>
            <RichText
              tagName="p"
              className="item-text"
              value={attributes.content}
              onChange={(content) => setAttributes({ content })}
              placeholder="Card description"
            />
            {attributes.buttonLink && (
              <div className="item-btn">
                <RichText
                  tagName="span"
                  className="btn btn-default"
                  value={attributes.buttonText}
                  onChange={(buttonText) => setAttributes({ buttonText })}
                  placeholder="Button text"
                />
              </div>
            )}
          </div>
        </div>

        <BlockControls>
          <MediaUploadCheck>
            <MediaUpload
              allowedTypes={['image']}
              value={attributes.imageId}
              onSelect={(media) => setAttributes({ imageId: media.id, imageUrl: media.url })}
              render={({ open }) => <ToolbarButton onClick={open} icon="format-image">Card image</ToolbarButton>}
            />
          </MediaUploadCheck>
        </BlockControls>

        <InspectorControls>
          <PanelBody title="Card settings" initialOpen={true}>
            <TextControl
              label="Button link"
              value={attributes.buttonLink || ''}
              __next40pxDefaultSize={true}
              onChange={(buttonLink) => setAttributes({ buttonLink })}
              placeholder="/about/mission-vision/"
              help="Leave blank to hide the button."
            />
            {attributes.buttonLink && (
              <TextControl
                label="Button text"
                value={attributes.buttonText || ''}
                __next40pxDefaultSize={true}
                onChange={(buttonText) => setAttributes({ buttonText })}
                placeholder="Learn More"
              />
            )}
          </PanelBody>
        </InspectorControls>
      </div>
    );
  },
  save: () => null,
});
