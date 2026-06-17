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
            gawk
            git
          ];

          shellHook = ''
            echo "nonfiction themestarter dev shell"
            echo "=================================="
            echo "Tools:"
            echo "  nf             $(nf version 2>/dev/null | head -1 | awk '{print $2}')"
            echo "  PHP            $(php -r 'echo PHP_VERSION;' 2>/dev/null)"
            echo "  Composer       $(composer --version 2>/dev/null | head -1)"
            echo "  PHP-CS-Fixer   $(php-cs-fixer --version 2>/dev/null | head -1)"
            echo "  PHPStan        $(phpstan --version 2>/dev/null)"
            echo "  PHPactor       $(phpactor --version 2>/dev/null | head -1)"
            echo "  Node           $(node -v)"
            echo ""
            echo "Local env:"
            echo "  nf env up            start WordPress"
            echo "  nf env show          show URLs, ports, and paths"
            echo "  nf env logs          tail WordPress logs"
            echo "  nf env shell         open a WordPress container shell"
            echo "  nf env wp -- <args>  run wp-cli"
            echo ""
            echo "Theme workflow:"
            echo "  nf theme tasks       list configured tasks"
            echo "  nf theme watch       watch theme assets"
            echo "  nf theme build       build production assets"
            echo "  nf theme check       run PHP and JavaScript checks"
            echo "  nf theme update      update Composer and npm dependencies"
            echo "  nf theme package     package a clean release artifact"
            echo ""
            echo "Help: nf help | nf env help | nf theme help"
          '';
        };
      }
    );
}
