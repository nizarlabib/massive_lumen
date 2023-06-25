<?php

/** @var \Laravel\Lumen\Routing\Router $router */
use Illuminate\Support\Str; // import library Str
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Laravel\Lumen\Http\Request as HttpRequest;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/key', function () {
    return Str::random(32);
});

$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('/register', ['uses'=> 'AuthController@register']);
    $router->post('/login', ['uses'=> 'AuthController@login']);
});

$router->group(['prefix' => 'public'], function () use ($router) {
    // WISATA
    $router->group(['prefix' => 'wisata'], function () use ($router) {
        $router->get('/', ['uses' =>'WisataController@getAllWisataForWisatawan']);
        $router->get('/{id_wisata}', ['uses' =>'WisataController@getWisataByIdForWisatawan']);
        $router->get('/kategori-wisata/{id_kategori_wisata}', ['uses' =>'WisataController@getAllWisataByKategoriWisataForWisatawan']);
    });

    // KATEGORI WISATA
    $router->group(['prefix' => 'kategori-wisata'], function () use ($router) {
        $router->get('/', ['uses' => 'KategoriWisataController@getAllKategoriWisata']);
        $router->get('/{id_kategori_wisata}', ['uses' =>'KategoriWisataController@getKategoriWisataById']); 
    });

    // ACARA
    $router->group(['prefix' => 'acara'], function () use ($router) {
        $router->get('/', ['uses' =>'AcaraController@getAllAcaraForWisatawan']);
        $router->get('/{id_acara}', ['uses' =>'AcaraController@getAcaraByIdForWisatawan']);
    });

    // GALERI WISATA
    $router->group(['prefix' => 'galeri-wisata'], function () use ($router) {
        $router->get('/', ['uses'=> 'GaleriWisataController@getAllGaleriWisata']);
        $router->get('/{id_galeri_wisata}', ['uses'=> 'GaleriWisataController@getGaleriWisataById']);
        $router->get('/wisata/{id_wisata}', ['uses'=> 'GaleriWisataController@getGaleriWisataByWisata']);
    });
    
    // GALERI ACARA
    $router->group(['prefix' => 'galeri-acara'], function () use ($router) {
        $router->get('/', ['uses'=> 'GaleriAcaraController@getAllGaleriAcara']);
        $router->get('/{id_galeri_acara}', ['uses'=> 'GaleriAcaraController@getGaleriAcaraById']);
        $router->get('/acara/{id_acara}', ['uses'=> 'GaleriAcaraController@getGaleriAcaraByAcara']);
    });
});


// USER ADMIN
$router->group(['prefix' => 'admin'], function () use ($router) {

    // DENGAN TOKEN
    $router->group(['middleware' => 'jwt.auth', 'middleware' => 'admin.auth'], function () use ($router) {
        
        // PENGELOLA  
        $router->group(['prefix' => 'pengelola'], function () use ($router) {
            $router->post('/', ['uses'=> 'AuthController@registerPengelola']); //create pengelola
            $router->put('/delete/{id_user}', ['uses' =>'UserController@delPengelola']); //delete pengelola
        });

        // ROLE USER
        $router->group(['prefix' => 'role-user'], function () use ($router) {
            $router->post('/', ['uses' => 'RoleUserController@createRoleUser']);
        });

        // KATEGORI WISATA
        $router->group(['prefix' => 'kategori-wisata'], function () use ($router) {
            $router->post('/', ['uses' => 'KategoriWisataController@createKategoriWisata']);
            $router->post('/update/{id_kategori_wisata}', ['uses' => 'KategoriWisataController@updateKategoriWisata']); 
            $router->put('/{id_kategori_wisata}', ['uses' => 'KategoriWisataController@delKategoriWisata']); 
        });
    });
    
    // TANPA TOKEN

    // KATEGORI WISATA 
    $router->group(['prefix' => 'kategori-wisata'], function () use ($router) {
        $router->get('/', ['uses' => 'KategoriWisataController@getAllKategoriWisata']);
        $router->get('/{id_kategori_wisata}', ['uses' =>'KategoriWisataController@getKategoriWisataById']); 
    });

    // PENGELOLA TANPA TOKEN
    $router->group(['prefix' => 'pengelola'], function () use ($router) {
        $router->get('/', ['uses' =>'UserController@getAllPengelola']); //read pengelola
        $router->get('/{id_user}', ['uses' =>'UserController@getPengelolaById']); //read pengelola
    });

    // WISATAWAN
    $router->group(['prefix' => 'wisatawan'], function () use ($router) {
        $router->get('/', ['uses' =>'UserController@getAllWisatawan']); //read WISATWAN
        $router->get('/{id_user}', ['uses' =>'UserController@getWisatawanById']); //read WISATWAN
    });
        
        // ROLE USER
    $router->group(['prefix' => 'role-user'], function () use ($router) {
        $router->get('/', ['uses' =>'RoleUserController@getAllRoleUser']);
        $router->get('/{id_role_user}', ['uses' =>'RoleUserController@getRoleUserById']);
    });

    //WISATA
    $router->group(['prefix' => 'wisata'], function () use ($router) {
        $router->get('/', ['uses' =>'WisataController@getAllWisataForWisatawan']);
        $router->get('/{id_wisata}', ['uses' =>'WisataController@getWisataByIdForWisatawan']);
        $router->get('/pengelola/{id_user}', ['uses' =>'WisataController@getAllWisataByIdPengelola']);
        $router->get('/kategori-wisata/{id_kategori_wisata}', ['uses' =>'WisataController@getAllWisataByKategoriWisataForPengelola']);
    });    

    // ACARA
    $router->group(['prefix' => 'acara'], function () use ($router) {
        $router->get('/', ['uses' =>'AcaraController@getAllAcaraForWisatawan']);
        $router->get('/{id_acara}', ['uses' =>'AcaraController@getAcaraByIdForWisatawan']);
        $router->get('pengelola/{id_user}', ['uses' =>'AcaraController@getAllAcaraByIdPengelola']);
    });     

    // GALERI WISATA
    $router->group(['prefix' => 'galeri-wisata'], function () use ($router) {
        $router->get('/', ['uses'=> 'GaleriWisataController@getAllGaleriWisata']);
        $router->get('/{id_galeri_wisata}', ['uses'=> 'GaleriWisataController@getGaleriWisataById']);
        $router->get('/wisata/{id_wisata}', ['uses'=> 'GaleriWisataController@getGaleriWisataByWisata']);
    });    
    
    // GALERI ACARA
    $router->group(['prefix' => 'galeri-acara'], function () use ($router) {
        $router->get('/', ['uses'=> 'GaleriAcaraController@getAllGaleriAcara']);
        $router->get('/{id_galeri_acara}', ['uses'=> 'GaleriAcaraController@getGaleriAcaraById']);
        $router->get('/acara/{id_acara}', ['uses'=> 'GaleriAcaraController@getGaleriAcaraByAcara']);
    });    
});

// USER PENGELOLA
$router->group(['prefix' => 'pengelola'], function () use ($router) {
    $router->group(['middleware' => 'jwt.auth', 'middleware' => 'pengelola.auth'], function () use ($router) {
        
        // TRANSAKSI
        $router->group(['prefix' => 'transaksi'], function () use ($router) {
            $router->get('/', ['uses' =>'TransaksiController@getAllTransaksi']);
            $router->get('/{id_transaksi}', ['uses' =>'TransaksiController@getTransaksiById']);
            $router->put('/{id_transaksi}', ['uses' => 'TransaksiController@delTransaksi']); 
            $router->put('/update/{id_transaksi}', ['uses' => 'TransaksiController@updateTransaksi']); 
        });

        // WISATAWAN
        $router->group(['prefix' => 'wisatawan'], function () use ($router) {
            $router->get('/', ['uses' =>'UserController@getAllWisatawanByPengelola']); //read WISATWAN
            $router->get('/{id_user}', ['uses' =>'UserController@getWisatawanByIdByPengelola']); //read WISATWAN
        });

        //CRUD WISATA
        $router->group(['prefix' => 'wisata'], function () use ($router) {
            $router->post('/', ['uses' => 'WisataController@createWisata']);
            $router->get('/', ['uses' =>'WisataController@getAllWisataForPengelola']);
            $router->get('/{id_wisata}', ['uses' =>'WisataController@getWisataByIdForPengelola']);
            $router->get('/kategori-wisata/{id_kategori_wisata}', ['uses' =>'WisataController@getAllWisataByKategoriWisataForPengelola']);
            $router->post('/update/{id_wisata}', ['uses' => 'WisataController@updateWisata']);
            $router->put('/delete/{id_wisata}', ['uses' =>'WisataController@delWisata']);
        });
        
        // CRUD ACARA
        $router->group(['prefix' => 'acara'], function () use ($router) {
            $router->post('/', ['uses' => 'AcaraController@createAcara']);
            $router->get('/', ['uses' =>'AcaraController@getAllAcaraForPengelola']);
            $router->get('/{id_acara}', ['uses' =>'AcaraController@getAcaraByIdForPengelola']);
            $router->post('/update/{id_acara}', ['uses' => 'AcaraController@updateAcara']); //
            $router->put('/delete/{id_acara}', ['uses' =>'AcaraController@delAcara']);
        });

        // GALERI WISATA
        $router->group(['prefix' => 'galeri-wisata'], function () use ($router) {
            $router->post('/', ['uses'=> 'GaleriWisataController@createGaleriWisata']);
            $router->get('/', ['uses'=> 'GaleriWisataController@getAllGaleriWisataForPengelola']);
            $router->get('/{id_galeri_wisata}', ['uses'=> 'GaleriWisataController@getGaleriWisataByIdForPengelola']);
            $router->put('/delete/{id_galeri_wisata}', ['uses'=> 'GaleriWisataController@delGaleriWisata']);
            $router->get('/wisata/{id_wisata}', ['uses'=> 'GaleriWisataController@getGaleriWisataByWisata']);
        });

        // GALERI ACARA
        $router->group(['prefix' => 'galeri-acara'], function () use ($router) {
            $router->post('/', ['uses'=> 'GaleriAcaraController@createGaleriAcara']);
            $router->get('/', ['uses'=> 'GaleriAcaraController@getAllGaleriAcaraForPengelola']);
            $router->get('/{id_galeri_acara}', ['uses'=> 'GaleriAcaraController@getGaleriAcaraByIdForPengelola']);
            $router->put('/delete/{id_galeri_acara}', ['uses'=> 'GaleriAcaraController@delGaleriAcara']);
            $router->get('/acara/{id_acara}', ['uses'=> 'GaleriAcaraController@getGaleriAcaraByAcara']);
        });
    });      
});

// USER WISATAWAN
$router->group(['prefix' => 'wisatawan'], function () use ($router) {
    $router->group(['middleware' => 'jwt.auth', 'middleware' => 'wisatawan.auth'], function () use ($router) {
        
        // UPDATE PROFIL
        $router->post('/update-profil', ['uses' => 'UserController@updateWisatawan']); 

        // TRANSAKSI
        $router->group(['prefix' => 'transaksi'], function () use ($router) {
            $router->post('/', ['uses' => 'TransaksiController@createTransaksi']);
            $router->get('/', ['uses' =>'TransaksiController@getAllTransaksiForWisatawan']);
            $router->get('/{id_transaksi}', ['uses' =>'TransaksiController@getTransaksiByIdForWisatawan']);
        });
    });
    
    // WISATA
    $router->group(['prefix' => 'wisata'], function () use ($router) {
        $router->get('/', ['uses' =>'WisataController@getAllWisataForWisatawan']);
        $router->get('/{id_wisata}', ['uses' =>'WisataController@getWisataByIdForWisatawan']);
        $router->get('/kategori-wisata/{id_kategori_wisata}', ['uses' =>'WisataController@getAllWisataByKategoriWisataForWisatawan']);
    });

    // KATEGORI WISATA
    $router->group(['prefix' => 'kategori-wisata'], function () use ($router) {
        $router->get('/', ['uses' => 'KategoriWisataController@getAllKategoriWisata']);
        $router->get('/{id_kategori_wisata}', ['uses' =>'KategoriWisataController@getKategoriWisataById']); 
    });
        
    // ACARA
    $router->group(['prefix' => 'acara'], function () use ($router) {
        $router->get('/', ['uses' =>'AcaraController@getAllAcaraForWisatawan']);
        $router->get('/{id_acara}', ['uses' =>'AcaraController@getAcaraByIdForWisatawan']);
    });
        
    // GALERI WISATA
    $router->group(['prefix' => 'galeri-wisata'], function () use ($router) {
        $router->get('/', ['uses'=> 'GaleriWisataController@getAllGaleriWisata']);
        $router->get('/{id_galeri_wisata}', ['uses'=> 'GaleriWisataController@getGaleriWisataById']);
        $router->get('/wisata/{id_wisata}', ['uses'=> 'GaleriWisataController@getGaleriWisataByWisata']);
    });
    
    // GALERI ACARA
    $router->group(['prefix' => 'galeri-acara'], function () use ($router) {
        $router->get('/', ['uses'=> 'GaleriAcaraController@getAllGaleriAcara']);
        $router->get('/{id_galeri_acara}', ['uses'=> 'GaleriAcaraController@getGaleriAcaraById']);
        $router->get('/acara/{id_acara}', ['uses'=> 'GaleriAcaraController@getGaleriAcaraByAcara']);
    });
        
});