{composerEnv, fetchurl, fetchgit ? null, fetchhg ? null, fetchsvn ? null, noDev ? false}:

let
  packages = {
    "container-interop/container-interop" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "container-interop-container-interop-79cbf1341c22ec75643d841642dd5d6acd83bdb8";
        src = fetchurl {
          url = https://api.github.com/repos/container-interop/container-interop/zipball/79cbf1341c22ec75643d841642dd5d6acd83bdb8;
          sha256 = "1pxm461g5flcq50yabr01nw8w17n3g7klpman9ps3im4z0604m52";
        };
      };
    };
    "dailymotion/sdk" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "dailymotion-sdk-0f558978785f9a6ab9e59c393041d4896550973b";
        src = fetchurl {
          url = https://api.github.com/repos/dailymotion/dailymotion-sdk-php/zipball/0f558978785f9a6ab9e59c393041d4896550973b;
          sha256 = "0bv0x0b8ag9718wxvmg3gf2yxb4fq7qj4y1q64gmrzkbmwmqz4a2";
        };
      };
    };
    "dandart/multiorm" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "dandart-multiorm-7d33a29a7678667b7a4c7c950c8d57fb05d9868e";
        src = fetchurl {
          url = https://api.github.com/repos/danwdart/multiorm/zipball/7d33a29a7678667b7a4c7c950c8d57fb05d9868e;
          sha256 = "1yp4m17pj6y9qb7dpbz73nvz9kzl1bnqya5rpip1pzz97dir60az";
        };
      };
    };
    "firebase/php-jwt" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "firebase-php-jwt-9984a4d3a32ae7673d6971ea00bae9d0a1abba0e";
        src = fetchurl {
          url = https://api.github.com/repos/firebase/php-jwt/zipball/9984a4d3a32ae7673d6971ea00bae9d0a1abba0e;
          sha256 = "00s8f75qsb7vzjmf9ca6nvp5pj59cri0fljvzvpr13s0cm4qbhm4";
        };
      };
    };
    "google/apiclient" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "google-apiclient-b69b8ac4bf6501793c389d4e013a79d09c85c5f2";
        src = fetchurl {
          url = https://api.github.com/repos/google/google-api-php-client/zipball/b69b8ac4bf6501793c389d4e013a79d09c85c5f2;
          sha256 = "1iwfn7vny7190431a3wq1d5axnbx1hr8ayldn0780z7xl597vxd2";
        };
      };
    };
    "google/apiclient-services" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "google-apiclient-services-5f474421169fc2d830cb46035cbc27f88c53ba8f";
        src = fetchurl {
          url = https://api.github.com/repos/google/google-api-php-client-services/zipball/5f474421169fc2d830cb46035cbc27f88c53ba8f;
          sha256 = "16ydz4v3lsz3zhg5gnmfn0rd2fs26hbgpyvxc1slx0qdlqgbn8ym";
        };
      };
    };
    "google/auth" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "google-auth-f3fc99fd621f339ee3d4de01bd6a709ed1396116";
        src = fetchurl {
          url = https://api.github.com/repos/google/google-auth-library-php/zipball/f3fc99fd621f339ee3d4de01bd6a709ed1396116;
          sha256 = "0csvdnxk7nyh7vksncnk5g4wn4w3s6fkswz58qfj8w6ip7ssfc9c";
        };
      };
    };
    "guzzlehttp/guzzle" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "guzzlehttp-guzzle-f4db5a78a5ea468d4831de7f0bf9d9415e348699";
        src = fetchurl {
          url = https://api.github.com/repos/guzzle/guzzle/zipball/f4db5a78a5ea468d4831de7f0bf9d9415e348699;
          sha256 = "12mk7vg71yy0z5rv6bwy9ckd7k0msjnfsnrd4p6q0vjkyqg9iq4r";
        };
      };
    };
    "guzzlehttp/promises" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "guzzlehttp-promises-a59da6cf61d80060647ff4d3eb2c03a2bc694646";
        src = fetchurl {
          url = https://api.github.com/repos/guzzle/promises/zipball/a59da6cf61d80060647ff4d3eb2c03a2bc694646;
          sha256 = "1kpl91fzalcgkcs16lpakvzcnbkry3id4ynx6xhq477p4fipdciz";
        };
      };
    };
    "guzzlehttp/psr7" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "guzzlehttp-psr7-f5b8a8512e2b58b0071a7280e39f14f72e05d87c";
        src = fetchurl {
          url = https://api.github.com/repos/guzzle/psr7/zipball/f5b8a8512e2b58b0071a7280e39f14f72e05d87c;
          sha256 = "1l901gxwqwk034idjw8nvcq58a0f036wnpaxayv21chh6v4gjmr1";
        };
      };
    };
    "jean85/pretty-package-versions" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "jean85-pretty-package-versions-3c8487fdd6c750ff3f10c32ddfdd2a7803c1d461";
        src = fetchurl {
          url = https://api.github.com/repos/Jean85/pretty-package-versions/zipball/3c8487fdd6c750ff3f10c32ddfdd2a7803c1d461;
          sha256 = "163yr70ijd87yxy5zzfjak123ddf2sr8j2bq98ccjmvy57dwjnji";
        };
      };
    };
    "misd/linkify" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "misd-linkify-3481b148806a23b4001712de645247a1a4dcc10a";
        src = fetchurl {
          url = https://api.github.com/repos/misd-service-development/php-linkify/zipball/3481b148806a23b4001712de645247a1a4dcc10a;
          sha256 = "0630268v6w1xwvrmvjym9hkdliii363d50pm5hi7gg1jr3ic0laq";
        };
      };
    };
    "monolog/monolog" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "monolog-monolog-fd8c787753b3a2ad11bc60c063cff1358a32a3b4";
        src = fetchurl {
          url = https://api.github.com/repos/Seldaek/monolog/zipball/fd8c787753b3a2ad11bc60c063cff1358a32a3b4;
          sha256 = "0avf3y8raw23krwdb7kw9qb5bsr5ls4i7qd2vh7hcds3qjixg3h9";
        };
      };
    };
    "mustache/mustache" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "mustache-mustache-fe8fe72e9d580591854de404cc59a1b83ca4d19e";
        src = fetchurl {
          url = https://api.github.com/repos/bobthecow/mustache.php/zipball/fe8fe72e9d580591854de404cc59a1b83ca4d19e;
          sha256 = "0hsayym3davg0h2a767xdp3m43wcs2l6s61p66fdpr2gw50rzpqh";
        };
      };
    };
    "nette/bootstrap" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "nette-bootstrap-804925787764d708a7782ea0d9382a310bb21968";
        src = fetchurl {
          url = https://api.github.com/repos/nette/bootstrap/zipball/804925787764d708a7782ea0d9382a310bb21968;
          sha256 = "08dm4z6lsmzyy3d7b2fsxdrzfk4vgjcyn16m6y7did250r5ncysp";
        };
      };
    };
    "nette/di" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "nette-di-a4b3be935b755f23aebea1ce33d7e3c832cdff98";
        src = fetchurl {
          url = https://api.github.com/repos/nette/di/zipball/a4b3be935b755f23aebea1ce33d7e3c832cdff98;
          sha256 = "0icrira5gl1q82ncnvis2mcsxyvp49w6m2w643wq9km74bf090gn";
        };
      };
    };
    "nette/finder" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "nette-finder-4d43a66d072c57d585bf08a3ef68d3587f7e9547";
        src = fetchurl {
          url = https://api.github.com/repos/nette/finder/zipball/4d43a66d072c57d585bf08a3ef68d3587f7e9547;
          sha256 = "0v98ybwqyxi48v9nw0dg24mrlllnnvxby6bn0zny7mmhigl1aw70";
        };
      };
    };
    "nette/neon" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "nette-neon-9eacd50553b26b53a3977bfb2fea2166d4331622";
        src = fetchurl {
          url = https://api.github.com/repos/nette/neon/zipball/9eacd50553b26b53a3977bfb2fea2166d4331622;
          sha256 = "05m58wanv66xk59ym6jnqsxyj8kpf09gsmmzcza12pkzpn33hwap";
        };
      };
    };
    "nette/php-generator" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "nette-php-generator-eb2dbc9c3409e9db40568109ca4994d51373b60c";
        src = fetchurl {
          url = https://api.github.com/repos/nette/php-generator/zipball/eb2dbc9c3409e9db40568109ca4994d51373b60c;
          sha256 = "03zwqjrcgx37mq12acgg6prj3vijyr7nnaip49bjxp1m873h6zpz";
        };
      };
    };
    "nette/robot-loader" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "nette-robot-loader-b703b4f5955831b0bcaacbd2f6af76021b056826";
        src = fetchurl {
          url = https://api.github.com/repos/nette/robot-loader/zipball/b703b4f5955831b0bcaacbd2f6af76021b056826;
          sha256 = "0rz0ph7n6d3gf5ccxmpyz8pjbs8dllxm77b2kfkp7dx9w23jxbdr";
        };
      };
    };
    "nette/utils" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "nette-utils-f1584033b5af945b470533b466b81a789d532034";
        src = fetchurl {
          url = https://api.github.com/repos/nette/utils/zipball/f1584033b5af945b470533b466b81a789d532034;
          sha256 = "04slca7638m7yh9b3zjh353iq3fsk572b3ikniswad55dplxqjnc";
        };
      };
    };
    "nikic/php-parser" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "nikic-php-parser-579f4ce846734a1cf55d6a531d00ca07a43e3cda";
        src = fetchurl {
          url = https://api.github.com/repos/nikic/PHP-Parser/zipball/579f4ce846734a1cf55d6a531d00ca07a43e3cda;
          sha256 = "1ky2gblmrs388aq6y3sxz4fq94wrdwg9fscva0rydrj2zfx147ji";
        };
      };
    };
    "ocramius/package-versions" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "ocramius-package-versions-ad8a245decad4897cc6b432743913dad0d69753c";
        src = fetchurl {
          url = https://api.github.com/repos/Ocramius/PackageVersions/zipball/ad8a245decad4897cc6b432743913dad0d69753c;
          sha256 = "07aclnzg5lwwf0fd3a41b3pmvcddbm2zxdfqffq9iziivcza2q16";
        };
      };
    };
    "php-amqplib/php-amqplib" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "php-amqplib-php-amqplib-f48748546e398d846134c594dfca9070c4c3b356";
        src = fetchurl {
          url = https://api.github.com/repos/php-amqplib/php-amqplib/zipball/f48748546e398d846134c594dfca9070c4c3b356;
          sha256 = "05pdc4z41khf4zhl53jgr2g7l7hykcg7fr9b4sazcjry1kv01m7a";
        };
      };
    };
    "phpseclib/phpseclib" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "phpseclib-phpseclib-c9a3fe35e20eb6eeaca716d6a23cde03f52d1558";
        src = fetchurl {
          url = https://api.github.com/repos/phpseclib/phpseclib/zipball/c9a3fe35e20eb6eeaca716d6a23cde03f52d1558;
          sha256 = "1ix951v3c4vr76abm2w03qafd3sjy02l1i2q3zr68wsga8k05lj1";
        };
      };
    };
    "phpstan/phpdoc-parser" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "phpstan-phpdoc-parser-08d714b2f0bc0a2bf9407255d5bb634669b7065c";
        src = fetchurl {
          url = https://api.github.com/repos/phpstan/phpdoc-parser/zipball/08d714b2f0bc0a2bf9407255d5bb634669b7065c;
          sha256 = "0dijhlzf6xw87wcd53whzmsnrvfzc3clqyf5axskxlmqpjl392wh";
        };
      };
    };
    "phpstan/phpstan" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "phpstan-phpstan-ef60e5cc0a32ddb2637523dafef966e0aac1e16f";
        src = fetchurl {
          url = https://api.github.com/repos/phpstan/phpstan/zipball/ef60e5cc0a32ddb2637523dafef966e0aac1e16f;
          sha256 = "1f9h2k9xsi7g1xj8mh7y2fzllsgv2sy60sycc6hl1iy052g6drh6";
        };
      };
    };
    "psr/cache" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "psr-cache-d11b50ad223250cf17b86e38383413f5a6764bf8";
        src = fetchurl {
          url = https://api.github.com/repos/php-fig/cache/zipball/d11b50ad223250cf17b86e38383413f5a6764bf8;
          sha256 = "06i2k3dx3b4lgn9a4v1dlgv8l9wcl4kl7vzhh63lbji0q96hv8qz";
        };
      };
    };
    "psr/container" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "psr-container-b7ce3b176482dbbc1245ebf52b181af44c2cf55f";
        src = fetchurl {
          url = https://api.github.com/repos/php-fig/container/zipball/b7ce3b176482dbbc1245ebf52b181af44c2cf55f;
          sha256 = "0rkz64vgwb0gfi09klvgay4qnw993l1dc03vyip7d7m2zxi6cy4j";
        };
      };
    };
    "psr/http-message" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "psr-http-message-f6561bf28d520154e4b0ec72be95418abe6d9363";
        src = fetchurl {
          url = https://api.github.com/repos/php-fig/http-message/zipball/f6561bf28d520154e4b0ec72be95418abe6d9363;
          sha256 = "195dd67hva9bmr52iadr4kyp2gw2f5l51lplfiay2pv6l9y4cf45";
        };
      };
    };
    "psr/log" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "psr-log-4ebe3a8bf773a19edfe0a84b6585ba3d401b724d";
        src = fetchurl {
          url = https://api.github.com/repos/php-fig/log/zipball/4ebe3a8bf773a19edfe0a84b6585ba3d401b724d;
          sha256 = "1mlcv17fjw39bjpck176ah1z393b6pnbw3jqhhrblj27c70785md";
        };
      };
    };
    "robmorgan/phinx" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "robmorgan-phinx-b8c4fc088c0c354eab4b21791a0dcf4379ac9ae3";
        src = fetchurl {
          url = https://api.github.com/repos/cakephp/phinx/zipball/b8c4fc088c0c354eab4b21791a0dcf4379ac9ae3;
          sha256 = "1kwjh1hbcdsm122n6l14iqq12721sfp358qn5qhv2a9rlfk7jam5";
        };
      };
    };
    "squizlabs/php_codesniffer" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "squizlabs-php_codesniffer-d7c00c3000ac0ce79c96fcbfef86b49a71158cd1";
        src = fetchurl {
          url = https://api.github.com/repos/squizlabs/PHP_CodeSniffer/zipball/d7c00c3000ac0ce79c96fcbfef86b49a71158cd1;
          sha256 = "0nzac879c26yhasglki9pc8k04czb1wv0wwcadw7fwc51zay4606";
        };
      };
    };
    "symfony/config" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-config-0356e6d5298e9e72212c0bad65c2f1b49e42d622";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/config/zipball/0356e6d5298e9e72212c0bad65c2f1b49e42d622;
          sha256 = "0n02hdsh6dri3i1z4yxk1rrhxwabzyj8lw4xp57q1pp4mj4hxfmf";
        };
      };
    };
    "symfony/console" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-console-de8cf039eacdec59d83f7def67e3b8ff5ed46714";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/console/zipball/de8cf039eacdec59d83f7def67e3b8ff5ed46714;
          sha256 = "1lr6l3ifjr269hvn0a8wh4d6iyvhqypps5j61rmxjy9phsd3fz2j";
        };
      };
    };
    "symfony/filesystem" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-filesystem-8c2868641d0c4885eee9c12a89c2b695eb1985cd";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/filesystem/zipball/8c2868641d0c4885eee9c12a89c2b695eb1985cd;
          sha256 = "0q9nimy5ggn9377c0csdhh4x3j4x2ir2akj00csdn4v0vw7ky311";
        };
      };
    };
    "symfony/finder" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-finder-c9cdda4dc4a3182d8d6daeebce4a25fef078ea4c";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/finder/zipball/c9cdda4dc4a3182d8d6daeebce4a25fef078ea4c;
          sha256 = "1scviigalhsdc1fl1lqlc78yzcd2v5wnpp2n72n1104ypvca0amj";
        };
      };
    };
    "symfony/polyfill-mbstring" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-polyfill-mbstring-2ec8b39c38cb16674bbf3fea2b6ce5bf117e1296";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/polyfill-mbstring/zipball/2ec8b39c38cb16674bbf3fea2b6ce5bf117e1296;
          sha256 = "0rk5nfgxmr7i1ss67zkm25vk4wpgvhlj11lpsljr6x4400g8f33y";
        };
      };
    };
    "symfony/yaml" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "symfony-yaml-a5ee52d155f06ad23b19eb63c31228ff56ad1116";
        src = fetchurl {
          url = https://api.github.com/repos/symfony/yaml/zipball/a5ee52d155f06ad23b19eb63c31228ff56ad1116;
          sha256 = "0d8cnywybz46wpxvrbc3vjaqgrmja67bg2ij61dk1wwkazrapy4g";
        };
      };
    };
    "vimeo/vimeo-api" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "vimeo-vimeo-api-46820e2d777362849137c1eb14f5cdb657c289ea";
        src = fetchurl {
          url = https://api.github.com/repos/vimeo/vimeo.php/zipball/46820e2d777362849137c1eb14f5cdb657c289ea;
          sha256 = "02vl18785bvds4425vmdh3647fpf0bm6m5yr9y1hja24b3l5bkd8";
        };
      };
    };
    "zendframework/zend-escaper" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "zendframework-zend-escaper-2dcd14b61a72d8b8e27d579c6344e12c26141d4e";
        src = fetchurl {
          url = https://api.github.com/repos/zendframework/zend-escaper/zipball/2dcd14b61a72d8b8e27d579c6344e12c26141d4e;
          sha256 = "0izsdkcra281a962c8j2v289aqc9v5hk4683vhbphyjqaw7k1s2m";
        };
      };
    };
    "zendframework/zend-http" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "zendframework-zend-http-78aa510c0ea64bfb2aa234f50c4f232c9531acfa";
        src = fetchurl {
          url = https://api.github.com/repos/zendframework/zend-http/zipball/78aa510c0ea64bfb2aa234f50c4f232c9531acfa;
          sha256 = "05y765mb6np8lslncw0q6cf7rm63q93lrq33vhm8pkgzr5sjmdf6";
        };
      };
    };
    "zendframework/zend-json" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "zendframework-zend-json-4c8705dbe4ad7d7e51b2876c5b9eea0ef916ba28";
        src = fetchurl {
          url = https://api.github.com/repos/zendframework/zend-json/zipball/4c8705dbe4ad7d7e51b2876c5b9eea0ef916ba28;
          sha256 = "1xhra9k237biwn24m2nrxqm31a8iv0fl3k8dj2panxf5cj6wpvs9";
        };
      };
    };
    "zendframework/zend-loader" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "zendframework-zend-loader-c5fd2f071bde071f4363def7dea8dec7393e135c";
        src = fetchurl {
          url = https://api.github.com/repos/zendframework/zend-loader/zipball/c5fd2f071bde071f4363def7dea8dec7393e135c;
          sha256 = "092war7ckvr2rga9kgx62snjs5s6z8iy2ni6yywls7671spfw9qd";
        };
      };
    };
    "zendframework/zend-stdlib" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "zendframework-zend-stdlib-debedcfc373a293f9250cc9aa03cf121428c8e78";
        src = fetchurl {
          url = https://api.github.com/repos/zendframework/zend-stdlib/zipball/debedcfc373a293f9250cc9aa03cf121428c8e78;
          sha256 = "13czva2qdb1sdqba3mr5yh8ggjb8zyn40fw9872czjfiafljdgzm";
        };
      };
    };
    "zendframework/zend-uri" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "zendframework-zend-uri-0bf717a239432b1a1675ae314f7c4acd742749ed";
        src = fetchurl {
          url = https://api.github.com/repos/zendframework/zend-uri/zipball/0bf717a239432b1a1675ae314f7c4acd742749ed;
          sha256 = "1d1xjk1dff5933x9dvkw9sr8pyyjs708h9r4aak3piravy2vrfsk";
        };
      };
    };
    "zendframework/zend-validator" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "zendframework-zend-validator-010084ddbd33299bf51ea6f0e07f8f4e8bd832a8";
        src = fetchurl {
          url = https://api.github.com/repos/zendframework/zend-validator/zipball/010084ddbd33299bf51ea6f0e07f8f4e8bd832a8;
          sha256 = "0llg1yidl5adfc7rzchpm6kcfdcnwfj592hnsx6c68k33mjq8sfy";
        };
      };
    };
    "zendframework/zend-version" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "zendframework-zend-version-e30c55dc394eaf396f0347887af0a7bef471fe08";
        src = fetchurl {
          url = https://api.github.com/repos/zendframework/zend-version/zipball/e30c55dc394eaf396f0347887af0a7bef471fe08;
          sha256 = "0gkhisqkcgyf7vsnbvq3554zxbmr7k9srn143bky3wlj6ndbb5aj";
        };
      };
    };
    "zendframework/zendframework1" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "zendframework-zendframework1-a90f3a8d71e0788020f730da83674b7312bd3b16";
        src = fetchurl {
          url = https://api.github.com/repos/zendframework/zf1/zipball/a90f3a8d71e0788020f730da83674b7312bd3b16;
          sha256 = "020pi1x7f5vcdprhwmn69qp0hdrdgbp3n13nzrxd3bv0gpygcmsg";
        };
      };
    };
    "zendframework/zendgdata" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "zendframework-zendgdata-333312548b2471642284a75d8804957b58fa26ab";
        src = fetchurl {
          url = https://api.github.com/repos/zendframework/ZendGData/zipball/333312548b2471642284a75d8804957b58fa26ab;
          sha256 = "0pz12vdj7a9ryiviindjnq33m2nvbfs6pg28iffngzx5v28ydrfh";
        };
      };
    };
  };
  devPackages = {
    "consistence/coding-standard" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "consistence-coding-standard-e273382d2ca04dff0be8a6d9733647f93fc72066";
        src = fetchurl {
          url = https://api.github.com/repos/consistence/coding-standard/zipball/e273382d2ca04dff0be8a6d9733647f93fc72066;
          sha256 = "0caq3j0nm8pzxq1khqygz810dm5xfvjy8as7i38vxavg3p6l60jg";
        };
      };
    };
    "doctrine/instantiator" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "doctrine-instantiator-185b8868aa9bf7159f5f953ed5afb2d7fcdc3bda";
        src = fetchurl {
          url = https://api.github.com/repos/doctrine/instantiator/zipball/185b8868aa9bf7159f5f953ed5afb2d7fcdc3bda;
          sha256 = "1mah9a6mb30qad1zryzjain2dxw29d8h4bjkbcs3srpm3p891msy";
        };
      };
    };
    "hamcrest/hamcrest-php" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "hamcrest-hamcrest-php-776503d3a8e85d4f9a1148614f95b7a608b046ad";
        src = fetchurl {
          url = https://api.github.com/repos/hamcrest/hamcrest-php/zipball/776503d3a8e85d4f9a1148614f95b7a608b046ad;
          sha256 = "12f2xsamhcksxcma4yzmm4clmhms1lz2aw4391zmb7y6agpwvjma";
        };
      };
    };
    "jakub-onderka/php-parallel-lint" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "jakub-onderka-php-parallel-lint-2ead2e4043ab125bee9554f356e0a86742c2d4fa";
        src = fetchurl {
          url = https://api.github.com/repos/JakubOnderka/PHP-Parallel-Lint/zipball/2ead2e4043ab125bee9554f356e0a86742c2d4fa;
          sha256 = "18j5cjr4kslk2p8jisgxf2q15y6cjlwin4ywg99g0rnmvc15k068";
        };
      };
    };
    "mockery/mockery" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "mockery-mockery-5bebcc3b33a606efe8ef1550e39e018626374f1f";
        src = fetchurl {
          url = https://api.github.com/repos/mockery/mockery/zipball/5bebcc3b33a606efe8ef1550e39e018626374f1f;
          sha256 = "0svs921vcqsjhbxajpg3sibbcvmaisabd4cvr066r8m21kvliih2";
        };
      };
    };
    "myclabs/deep-copy" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "myclabs-deep-copy-3b8a3a99ba1f6a3952ac2747d989303cbd6b7a3e";
        src = fetchurl {
          url = https://api.github.com/repos/myclabs/DeepCopy/zipball/3b8a3a99ba1f6a3952ac2747d989303cbd6b7a3e;
          sha256 = "1mxn444j48gnk11pjm2b4ixxa8y6lcmrqslshm7zwqbl96k4mx48";
        };
      };
    };
    "phar-io/manifest" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "phar-io-manifest-2df402786ab5368a0169091f61a7c1e0eb6852d0";
        src = fetchurl {
          url = https://api.github.com/repos/phar-io/manifest/zipball/2df402786ab5368a0169091f61a7c1e0eb6852d0;
          sha256 = "0l6n4z4mx84xbc0bjjyf0gxn3c1x2vq9aals46yj98wywp4sj7hx";
        };
      };
    };
    "phar-io/version" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "phar-io-version-a70c0ced4be299a63d32fa96d9281d03e94041df";
        src = fetchurl {
          url = https://api.github.com/repos/phar-io/version/zipball/a70c0ced4be299a63d32fa96d9281d03e94041df;
          sha256 = "07arsyb38pczdzvmnz785yf34rza6znv3z6db6y9d1yfyfrx6dix";
        };
      };
    };
    "phpdocumentor/reflection-common" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "phpdocumentor-reflection-common-21bdeb5f65d7ebf9f43b1b25d404f87deab5bfb6";
        src = fetchurl {
          url = https://api.github.com/repos/phpDocumentor/ReflectionCommon/zipball/21bdeb5f65d7ebf9f43b1b25d404f87deab5bfb6;
          sha256 = "1yaf1zg9lnkfnq2ndpviv0hg5bza9vjvv5l4wgcn25lx1p8a94w2";
        };
      };
    };
    "phpdocumentor/reflection-docblock" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "phpdocumentor-reflection-docblock-66465776cfc249844bde6d117abff1d22e06c2da";
        src = fetchurl {
          url = https://api.github.com/repos/phpDocumentor/ReflectionDocBlock/zipball/66465776cfc249844bde6d117abff1d22e06c2da;
          sha256 = "1kvdg70p18s4gda5gqh5hzj5s5b6y3067vyzrxrji66ri3z5ibzy";
        };
      };
    };
    "phpdocumentor/type-resolver" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "phpdocumentor-type-resolver-9c977708995954784726e25d0cd1dddf4e65b0f7";
        src = fetchurl {
          url = https://api.github.com/repos/phpDocumentor/TypeResolver/zipball/9c977708995954784726e25d0cd1dddf4e65b0f7;
          sha256 = "0h888r2iy2290yp9i3fij8wd5b7960yi7yn1rwh26x1xxd83n2mb";
        };
      };
    };
    "phpspec/prophecy" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "phpspec-prophecy-e4ed002c67da8eceb0eb8ddb8b3847bb53c5c2bf";
        src = fetchurl {
          url = https://api.github.com/repos/phpspec/prophecy/zipball/e4ed002c67da8eceb0eb8ddb8b3847bb53c5c2bf;
          sha256 = "1i6amza0qaq8jq3isz0vjlm0x6mrsl0k2zk86rsl4chr2wp4np7y";
        };
      };
    };
    "phpunit/php-code-coverage" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "phpunit-php-code-coverage-661f34d0bd3f1a7225ef491a70a020ad23a057a1";
        src = fetchurl {
          url = https://api.github.com/repos/sebastianbergmann/php-code-coverage/zipball/661f34d0bd3f1a7225ef491a70a020ad23a057a1;
          sha256 = "0j8i59q7acsbjzvgpkzlpr0r1556l1qnmr8189r7qr4x9v4sqr8s";
        };
      };
    };
    "phpunit/php-file-iterator" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "phpunit-php-file-iterator-730b01bc3e867237eaac355e06a36b85dd93a8b4";
        src = fetchurl {
          url = https://api.github.com/repos/sebastianbergmann/php-file-iterator/zipball/730b01bc3e867237eaac355e06a36b85dd93a8b4;
          sha256 = "0kbg907g9hrx7pv8v0wnf4ifqywdgvigq6y6z00lyhgd0b8is060";
        };
      };
    };
    "phpunit/php-text-template" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "phpunit-php-text-template-31f8b717e51d9a2afca6c9f046f5d69fc27c8686";
        src = fetchurl {
          url = https://api.github.com/repos/sebastianbergmann/php-text-template/zipball/31f8b717e51d9a2afca6c9f046f5d69fc27c8686;
          sha256 = "1y03m38qqvsbvyakd72v4dram81dw3swyn5jpss153i5nmqr4p76";
        };
      };
    };
    "phpunit/php-timer" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "phpunit-php-timer-3dcf38ca72b158baf0bc245e9184d3fdffa9c46f";
        src = fetchurl {
          url = https://api.github.com/repos/sebastianbergmann/php-timer/zipball/3dcf38ca72b158baf0bc245e9184d3fdffa9c46f;
          sha256 = "1j04r0hqzrv6m1jk5nb92k2nnana72nscqpfk3rgv3fzrrv69ljr";
        };
      };
    };
    "phpunit/php-token-stream" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "phpunit-php-token-stream-791198a2c6254db10131eecfe8c06670700904db";
        src = fetchurl {
          url = https://api.github.com/repos/sebastianbergmann/php-token-stream/zipball/791198a2c6254db10131eecfe8c06670700904db;
          sha256 = "03i9259r9mjib2ipdkavkq6di66mrsga6kzc7rq5pglrhfiiil4s";
        };
      };
    };
    "phpunit/phpunit" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "phpunit-phpunit-83d27937a310f2984fd575686138597147bdc7df";
        src = fetchurl {
          url = https://api.github.com/repos/sebastianbergmann/phpunit/zipball/83d27937a310f2984fd575686138597147bdc7df;
          sha256 = "0zrpfmkjckvvczybrr7w1gjj5bfggbdqc01nk238dknszah6dlls";
        };
      };
    };
    "phpunit/phpunit-mock-objects" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "phpunit-phpunit-mock-objects-283b9f4f670e3a6fd6c4ff95c51a952eb5c75933";
        src = fetchurl {
          url = https://api.github.com/repos/sebastianbergmann/phpunit-mock-objects/zipball/283b9f4f670e3a6fd6c4ff95c51a952eb5c75933;
          sha256 = "0yax638hb3i5j2zgsyap8sq5g9n48q31ifsxbvqkxvvpvd96f3z0";
        };
      };
    };
    "sebastian/code-unit-reverse-lookup" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "sebastian-code-unit-reverse-lookup-4419fcdb5eabb9caa61a27c7a1db532a6b55dd18";
        src = fetchurl {
          url = https://api.github.com/repos/sebastianbergmann/code-unit-reverse-lookup/zipball/4419fcdb5eabb9caa61a27c7a1db532a6b55dd18;
          sha256 = "0n0bygv2vx1l7af8szbcbn5bpr4axrgvkzd0m348m8ckmk8akvs8";
        };
      };
    };
    "sebastian/comparator" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "sebastian-comparator-b11c729f95109b56a0fe9650c6a63a0fcd8c439f";
        src = fetchurl {
          url = https://api.github.com/repos/sebastianbergmann/comparator/zipball/b11c729f95109b56a0fe9650c6a63a0fcd8c439f;
          sha256 = "00ci7akjar3mjdpgwfrf1b123bp9fcivyvjzv8qv8zqcd593zwzx";
        };
      };
    };
    "sebastian/diff" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "sebastian-diff-347c1d8b49c5c3ee30c7040ea6fc446790e6bddd";
        src = fetchurl {
          url = https://api.github.com/repos/sebastianbergmann/diff/zipball/347c1d8b49c5c3ee30c7040ea6fc446790e6bddd;
          sha256 = "0bca0q624zjwm555irbb2vv0y6dy0plbh01nlp74bxzmd3lra88a";
        };
      };
    };
    "sebastian/environment" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "sebastian-environment-cd0871b3975fb7fc44d11314fd1ee20925fce4f5";
        src = fetchurl {
          url = https://api.github.com/repos/sebastianbergmann/environment/zipball/cd0871b3975fb7fc44d11314fd1ee20925fce4f5;
          sha256 = "1b2jgfi67xmspijyzrgn23cycdw0rkfx5q3llhvz6gkwyxgmqxnm";
        };
      };
    };
    "sebastian/exporter" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "sebastian-exporter-234199f4528de6d12aaa58b612e98f7d36adb937";
        src = fetchurl {
          url = https://api.github.com/repos/sebastianbergmann/exporter/zipball/234199f4528de6d12aaa58b612e98f7d36adb937;
          sha256 = "061rkix1dws8wbjggf6c8s3kjjv3ws1yacg70zp7cc5wk3z1ar8y";
        };
      };
    };
    "sebastian/global-state" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "sebastian-global-state-e8ba02eed7bbbb9e59e43dedd3dddeff4a56b0c4";
        src = fetchurl {
          url = https://api.github.com/repos/sebastianbergmann/global-state/zipball/e8ba02eed7bbbb9e59e43dedd3dddeff4a56b0c4;
          sha256 = "1489kfvz0gg6jprakr43mjkminlhpsimcdrrxkmsm6mmhahbgjnf";
        };
      };
    };
    "sebastian/object-enumerator" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "sebastian-object-enumerator-7cfd9e65d11ffb5af41198476395774d4c8a84c5";
        src = fetchurl {
          url = https://api.github.com/repos/sebastianbergmann/object-enumerator/zipball/7cfd9e65d11ffb5af41198476395774d4c8a84c5;
          sha256 = "00z5wzh19z1drnh52d27gflqm7dyisp96c29zyxrgsdccv1wss3m";
        };
      };
    };
    "sebastian/object-reflector" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "sebastian-object-reflector-773f97c67f28de00d397be301821b06708fca0be";
        src = fetchurl {
          url = https://api.github.com/repos/sebastianbergmann/object-reflector/zipball/773f97c67f28de00d397be301821b06708fca0be;
          sha256 = "1rq5wwf7smdbbz3mj46hmjc643bbsm2b6cnnggmawyls479qmxlk";
        };
      };
    };
    "sebastian/recursion-context" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "sebastian-recursion-context-5b0cd723502bac3b006cbf3dbf7a1e3fcefe4fa8";
        src = fetchurl {
          url = https://api.github.com/repos/sebastianbergmann/recursion-context/zipball/5b0cd723502bac3b006cbf3dbf7a1e3fcefe4fa8;
          sha256 = "0p4j54bxriciw67g7l8zy1wa472di0b8f8mxs4fdvm37asz2s6vd";
        };
      };
    };
    "sebastian/resource-operations" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "sebastian-resource-operations-ce990bb21759f94aeafd30209e8cfcdfa8bc3f52";
        src = fetchurl {
          url = https://api.github.com/repos/sebastianbergmann/resource-operations/zipball/ce990bb21759f94aeafd30209e8cfcdfa8bc3f52;
          sha256 = "19jfc8xzkyycglrcz85sv3ajmxvxwkw4sid5l4i8g6wmz9npbsxl";
        };
      };
    };
    "sebastian/version" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "sebastian-version-99732be0ddb3361e16ad77b68ba41efc8e979019";
        src = fetchurl {
          url = https://api.github.com/repos/sebastianbergmann/version/zipball/99732be0ddb3361e16ad77b68ba41efc8e979019;
          sha256 = "0wrw5hskz2hg5aph9r1fhnngfrcvhws1pgs0lfrwindy066z6fj7";
        };
      };
    };
    "slevomat/coding-standard" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "slevomat-coding-standard-823ba04bdcfe642ec2b912e4650d6e33d48c2ef7";
        src = fetchurl {
          url = https://api.github.com/repos/slevomat/coding-standard/zipball/823ba04bdcfe642ec2b912e4650d6e33d48c2ef7;
          sha256 = "0k8gqc7mv66y3kp82x8vyb4xgmrsamcd0j88jn6kzqnnx63l9l0k";
        };
      };
    };
    "theseer/tokenizer" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "theseer-tokenizer-cb2f008f3f05af2893a87208fe6a6c4985483f8b";
        src = fetchurl {
          url = https://api.github.com/repos/theseer/tokenizer/zipball/cb2f008f3f05af2893a87208fe6a6c4985483f8b;
          sha256 = "0jpr12k9rjvx223vxy5m3shdvlimyk2r4s332bcq6bn2nfw5wnnb";
        };
      };
    };
    "vlucas/phpdotenv" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "vlucas-phpdotenv-3cc116adbe4b11be5ec557bf1d24dc5e3a21d18c";
        src = fetchurl {
          url = https://api.github.com/repos/vlucas/phpdotenv/zipball/3cc116adbe4b11be5ec557bf1d24dc5e3a21d18c;
          sha256 = "1c1n4x17pd70w6gz8nm8vpkigf1975yqsk6dr80zqkxisx4klldi";
        };
      };
    };
    "webmozart/assert" = {
      targetDir = "";
      src = composerEnv.buildZipPackage {
        name = "webmozart-assert-2db61e59ff05fe5126d152bd0655c9ea113e550f";
        src = fetchurl {
          url = https://api.github.com/repos/webmozart/assert/zipball/2db61e59ff05fe5126d152bd0655c9ea113e550f;
          sha256 = "1w3dih57lmli6xb526xca3604vxqr7wmj39xw5vcfshlk97yzgax";
        };
      };
    };
  };
in
composerEnv.buildPackage {
  inherit packages devPackages noDev;
  name = "kathiedart-projectchaplin";
  src = ./.;
  executable = false;
  symlinkDependencies = false;
  meta = {
    homepage = https://projectchaplin.com;
    license = "AGPL-3.0";
  };
}