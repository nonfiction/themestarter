const { registerBlockType: wpRegisterBlockType } = wp.blocks;

export function registerBlockType(json = {}, override = {}) {
  const args = { ...json, ...override };
  const name = args.name || false;
  const icon = args.icon || "";

  args.icon = icon.replace("dashicons-", "");

  if (name) {
    wpRegisterBlockType(name, args);
  }
}

const nf = {
  registerBlockType,
};

export default nf;
