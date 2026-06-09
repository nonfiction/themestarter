{ pkgs, ... }:
let
  prettier = pkgs.writeShellApplication {
    name = "treefmt-prettier";
    text = ''
      args=()

      for path in "$@"; do
        case "$path" in
          theme/*) args+=("''${path#theme/}") ;;
          *) args+=("../$path") ;;
        esac
      done

      cd theme

      if [ ! -x ./node_modules/.bin/prettier ]; then
        echo "Missing theme/node_modules/.bin/prettier. Run: cd theme && npm install" >&2
        exit 1
      fi

      exec ./node_modules/.bin/prettier \
        --config ./.prettierrc.json \
        --ignore-path ./.prettierignore \
        --write \
        "''${args[@]}"
    '';
  };
in
{
  projectRootFile = "flake.nix";

  programs.nixfmt.enable = true;

  programs.php-cs-fixer = {
    enable = true;
    configFile = "./theme/.php-cs-fixer.dist.php";
  };

  settings.formatter.prettier = {
    command = pkgs.lib.getExe prettier;
    includes = [
      "*.md"
      "**/*.md"
      "theme/**/*.css"
      "theme/**/*.html"
      "theme/**/*.js"
      "theme/**/*.json"
      "theme/**/*.twig"
      "theme/**/*.yaml"
      "theme/**/*.yml"
    ];
  };

  settings.global.excludes = [
    "theme/node_modules/**"
    "theme/vendor/**"
    "theme/assets/dist/**"
    "theme/dist/**"
  ];
}
