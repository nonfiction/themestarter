{
  description = "nonfiction theme development environment";

  inputs = {
    nixpkgs.url = "github:NixOS/nixpkgs/nixpkgs-unstable";
    flake-utils.url = "github:numtide/flake-utils";
    treefmt-nix.url = "github:numtide/treefmt-nix";
    treefmt-nix.inputs.nixpkgs.follows = "nixpkgs";
    nf.url = "github:nonfiction/nf";
    nf.inputs.nixpkgs.follows = "nixpkgs";
    nf.inputs.flake-utils.follows = "flake-utils";
  };

  outputs = inputs:
    inputs.flake-utils.lib.eachDefaultSystem (
      system: let
        pkgs = inputs.nixpkgs.legacyPackages.${system};
        treefmtEval = inputs.treefmt-nix.lib.evalModule pkgs ./treefmt.nix;
        php = pkgs.php83.buildEnv {
          extensions = {
            all,
            enabled,
          }:
            enabled
            ++ [
              all.bz2
              all.curl
              all.exif
              all.fileinfo
              all.gd
              all.iconv
              all.imagick
              all.intl
              all.mbstring
              all.mysqlnd
              all.opcache
              all.pdo
              all.pdo_mysql
              all.xsl
              all.zip
              all.apcu
            ];
        };
      in {
        formatter = treefmtEval.config.build.wrapper;

        devShells.default = pkgs.mkShell {
          packages = with pkgs; [
            inputs.nf.packages.${system}.default
            treefmtEval.config.build.wrapper

            php
            php83Packages.composer
            php83Packages.php-cs-fixer
            phpstan
            phpactor

            nodejs_24
            docker-client
            git
          ];

          shellHook = ''
            echo "nonfiction theme dev shell"
            echo "==============================="
            echo "PHP:      $(php -v | head -1)"
            echo "Composer: $(composer --version 2>/dev/null)"
            echo "PHP-CS-Fixer: $(php-cs-fixer --version 2>/dev/null | head -1)"
            echo "PHPStan:      $(phpstan --version 2>/dev/null)"
            echo "PHPactor: $(phpactor --version 2>/dev/null | head -1)"
            echo "Node:     $(node -v)"
            echo ""
            echo "Common commands:"
            echo "  nf env up"
            echo "  nf env down"
            echo "  nf env logs"
            echo "  nf env reset"
            echo "  nf env wp"
            echo "  nf theme composer"
            echo "  nf theme composer format:php"
            echo "  nf theme composer check:php-style"
            echo "  nf theme npm"
            echo "  nf theme watch"
            echo "  nf theme build"
            echo "  nf theme package"
          '';
        };
      }
    );
}
