export function registerBlockType(metadata = {}, settings = {}) {
  const { registerBlockType: wpRegisterBlockType } = wp.blocks;
  const args = { ...metadata, ...settings };
  const name = args.name || false;

  if (!name) {
    return false;
  }

  if (typeof args.icon === 'string') {
    args.icon = args.icon.replace(/^dashicons-/, '');
  }

  return wpRegisterBlockType(name, args);
}
