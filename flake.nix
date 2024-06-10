{
  inputs = {
    nixpkgs.url = "github:nixos/nixpkgs/nixos-24.05";
    flake-utils.url = "github:numtide/flake-utils";
  };

  outputs = {
    self,
    nixpkgs,
    flake-utils,
  }: flake-utils.lib.eachDefaultSystem (system:
    let
      overlays = [];
      pkgs = import nixpkgs {
        inherit system overlays;
      };
      prepareEnv = ''
        echo ""
        echo "> Run PHPStan"
        echo "> \$ composer analyze"
        echo ">"
        echo "> Run PHPUnit"
        echo "> \$ composer test"
      '';
    in {
      devShells = rec {
        # PHP 8.1
        php81 = pkgs.mkShell {
          buildInputs = [
            pkgs.php81
            pkgs.php81Packages.composer
          ];

          shellHook = prepareEnv;
        };

        # PHP 8.2
        php82 = pkgs.mkShell {
          buildInputs = [
            pkgs.php82
            pkgs.php82Packages.composer
          ];

          shellHook = prepareEnv;
        };

        # PHP 8.3
        php83 = pkgs.mkShell {
          buildInputs = [
            pkgs.php83
            pkgs.php83Packages.composer
          ];

          shellHook = prepareEnv;
        };

        default = php82;
      };

      formatter = pkgs.alejandra;
    }
  );
}

