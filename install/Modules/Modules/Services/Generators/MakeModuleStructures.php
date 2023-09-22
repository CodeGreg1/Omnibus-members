<?php

namespace Modules\Modules\Services\Generators;

use Illuminate\Http\File;
use Illuminate\Support\Str;
use Modules\Base\Support\CrudTypes;
use Illuminate\Filesystem\Filesystem;
use Modules\Modules\Support\CrudTypeReplacements;
use Modules\Modules\Services\Generators\ModuleGeneratorHelper;

class MakeModuleStructures 
{
	/**
	 * The module path
	 * 
	 * @var string $path
	 */
	protected $path;

    /**
     * @var array $replacements
     */
    protected $replacements;

    /**
     * @var array $moduleAttributes
     */
    protected $moduleAttributes;

	/**
     * The laravel filesystem or null.
     *
     * @var \Illuminate\Filesystem\Filesystem|null
     */
    protected $filesystem;

    /**
     * @var array $structures
     */
	protected $structures = [
        'Resources' => [
            'assets' => [
                'js' => [
                    'dir_files' => [
                        [
                            'stub_path' => 'assets/js/$CRUD_LOWER$$STUDLY_NAME$.js.stub',
                            'type' => 'admin'
                        ],
                        [
                            'stub_path' => 'assets/js/$CRUD_LOWER$$STUDLY_NAME$Create.js.stub',
                            'type' => 'admin'
                        ],
                        [
                            'stub_path' => 'assets/js/$CRUD_LOWER$$STUDLY_NAME$Update.js.stub',
                            'type' => 'admin'
                        ],
                        [
                            'stub_path' => 'assets/js/$CRUD_LOWER$$STUDLY_NAME$Datatable.js.stub',
                            'type' => 'admin'
                        ],
                        [
                            'stub_path' => 'assets/js/$CRUD_LOWER$$STUDLY_NAME$Delete.js.stub',
                            'type' => 'admin'
                        ],
                        [
                            'stub_path' => 'assets/js/$CRUD_LOWER$$STUDLY_NAME$Upload.js.stub',
                            'type' => 'admin'
                        ],
                        [
                            'stub_path' => 'assets/js/$CRUD_LOWER$$STUDLY_NAME$.js.stub',
                            'type' => 'user'
                        ],
                        [
                            'stub_path' => 'assets/js/$CRUD_LOWER$$STUDLY_NAME$Create.js.stub',
                            'type' => 'user'
                        ],
                        [
                            'stub_path' => 'assets/js/$CRUD_LOWER$$STUDLY_NAME$Update.js.stub',
                            'type' => 'user'
                        ],
                        [
                            'stub_path' => 'assets/js/$CRUD_LOWER$$STUDLY_NAME$Datatable.js.stub',
                            'type' => 'user'
                        ],
                        [
                            'stub_path' => 'assets/js/$CRUD_LOWER$$STUDLY_NAME$Delete.js.stub',
                            'type' => 'user'
                        ],
                        [
                            'stub_path' => 'assets/js/$CRUD_LOWER$$STUDLY_NAME$Upload.js.stub',
                            'type' => 'user'
                        ]
                    ]
                ],
                'sass' => [
                    'dir_files' => [
                        [
                            'stub_path' => 'assets/sass/$CAMEL_NAME$.scss.stub'
                        ]
                    ]
                ]
            ],
            'views' => [
                'layouts' => [
                    'dir_files' => [
                        [
                            'stub_path' => 'views/layouts/master.blade.php.stub'
                        ]
                    ]
                ],
                'admin' => [
                    'dir_files' => [
                        [
                            'stub_path' => 'views/edit.blade.php.stub',
                            'type' => 'admin'
                        ],
                        [
                            'stub_path' => 'views/create.blade.php.stub',
                            'type' => 'admin'
                        ],
                        [
                            'stub_path' => 'views/index.blade.php.stub',
                            'type' => 'admin'
                        ],
                        [
                            'stub_path' => 'views/show.blade.php.stub',
                            'type' => 'admin'
                        ]
                    ]
                ],
                'user' => [
                    'dir_files' => [
                        [
                            'stub_path' => 'views/edit.blade.php.stub',
                            'type' => 'user'
                        ],
                        [
                            'stub_path' => 'views/create.blade.php.stub',
                            'type' => 'user'
                        ],
                        [
                            'stub_path' => 'views/index.blade.php.stub',
                            'type' => 'user'
                        ],
                        [
                            'stub_path' => 'views/show.blade.php.stub',
                            'type' => 'user'
                        ]
                    ]
                ]
            ],
            'lang' => [
                'dir_files' => [
                    [
                        'stub_path' => 'en.json.stub'
                    ]
                ]
            ]
        ],
        'Events' => [
            'dir_files' => [
                [
                    'stub_path' => '$STUDLY_NAME$Created.php.stub'
                ],
                [
                    'stub_path' => '$STUDLY_NAME$Updated.php.stub'
                ],
                [
                    'stub_path' => '$STUDLY_NAME$Deleted.php.stub'
                ],
                [
                    'stub_path' => '$STUDLY_NAME$Restored.php.stub'
                ],
                [
                    'stub_path' => '$STUDLY_NAME$ForceDeleted.php.stub'
                ]
            ]
        ],
        'Repositories' => [
            'dir_files' => [
                [
                    'stub_path' => '$MODEL_PLURAL$Repository.php.stub'
                ]
            ]
        ],
        'Models' => [
            'dir_files' => [
                [
                    'stub_path' => '$MODEL$.php.stub'
                ]
            ]
        ],
        'Providers' => [
            'dir_files' => [
                [
                    'stub_path' => '$STUDLY_NAME$ServiceProvider.php.stub'
                ],
                [
                    'stub_path' => 'RouteServiceProvider.php.stub'
                ]
            ]
        ],
        'Config' => [
            'dir_files' => [
                [
                    'stub_path' => 'config.php.stub'
                ]
            ]
        ],
        'Routes' => [
            'dir_files' => [
                [
                    'stub_path' => '$CRUD_LOWER$.php.stub',
                    'type' => 'admin'
                ],
                [
                    'stub_path' => '$CRUD_LOWER$.php.stub',
                    'type' => 'user'
                ],
                [
                    'stub_path' => 'api.php.stub'
                ]
            ]
        ],
        'Console' => [],
        'Database' => [
            'Migrations' => [
                'dir_files' => [
                    [
                        'stub_path' => 'Migrations/$SNAKE_DATE$_create_$TABLE_MODEL$_table.php.stub'
                    ]
                ]
            ],
            'Seeders' => [],
            'factories' => []
        ],
        'Http' => [
            'Requests' => [
                'dir_files' => [
                    [
                        'stub_path' => 'Requests/$CRUD_STUDLY$Store$MODEL$Request.php.stub',
                        'type' => 'admin'
                    ],
                    [
                        'stub_path' => 'Requests/$CRUD_STUDLY$Update$MODEL$Request.php.stub',
                        'type' => 'admin'
                    ],
                    [
                        'stub_path' => 'Requests/$CRUD_STUDLY$Store$MODEL$Request.php.stub',
                        'type' => 'user'
                    ],
                    [
                        'stub_path' => 'Requests/$CRUD_STUDLY$Update$MODEL$Request.php.stub',
                        'type' => 'user'
                    ],
                    [
                        'stub_path' => 'Requests/ApiStore$MODEL$Request.php.stub'
                    ],
                    [
                        'stub_path' => 'Requests/ApiUpdate$MODEL$Request.php.stub'
                    ]
                ]
            ],
            'Middleware',
            'Controllers' => [
                'Web' => [
                    'Admin' => [
                        'dir_files' => [
                            [
                                'stub_path' => 'Web/$STUDLY_NAME$Controller.php.stub',
                                'type' => 'admin'
                            ],
                            [
                                'stub_path' => 'Web/$STUDLY_NAME$DatatableController.php.stub',
                                'type' => 'admin'
                            ]
                        ]
                    ],
                    'User' => [
                        'dir_files' => [
                            [
                                'stub_path' => 'Web/$STUDLY_NAME$Controller.php.stub',
                                'type' => 'user'
                            ],
                            [
                                'stub_path' => 'Web/$STUDLY_NAME$DatatableController.php.stub',
                                'type' => 'user'
                            ]
                        ]
                    ]
                ],
                'Api' => [
                        'V1' => [
                            'dir_files' => [
                            [
                                'stub_path' => 'Api/V1/$STUDLY_NAME$Controller.php.stub',
                                'type' => 'admin'
                            ],
                            [
                                'stub_path' => 'Api/V1/$STUDLY_NAME$Controller.php.stub',
                                'type' => 'user'
                            ]
                        ]
                    ]
                ]
            ]
        ],
        'Tests' => [
            'Feature' => [],
            'Unit' => []
        ],
        'Transformers' => [
            'dir_files' => [
                [
                    'stub_path' => 'Transformers/$STUDLY_NAME$Resource.php.stub'
                ]
            ]
        ],
        'dir_files' => [
            [
                'stub_path' => 'package.json.stub'
            ],
            [
                'stub_path' => 'webpack.mix.js.stub'
            ],
            [
                'stub_path' => 'composer.json.stub'
            ],
            [
                'stub_path' => 'module.json.stub'
            ]
        ]
    ];

    /**
     * @param string $path
     * @param array $replacements
     * @param array $attributes
     */
    public function __construct($path, $replacements, $attributes) 
	{
		$this->path = $path;
        $this->replacements = $replacements;
        $this->moduleAttributes = $attributes;
		$this->filesystem = new Filesystem;
	}

    /**
     * Handle making module directory
     * 
     * @return void
     */
    public function make() 
    {
    	$this->generate($this->structures);
    }

    /**
     * Generate modules directory structure
     * 
     * @param array $folders The folder structure
     * @param string|null $parentKey The parent key
     * @param string|null $parentDir  The parent directory with nested support
     * 
     * @return void
     */
    protected function generate($folders = [], $parentKey = null, $parentDir = null) 
    {
        foreach ($folders as $dir => $subDirs) {

            if(!$parentDir) {
                $parentDirV = null;
            } else {
                $parentDirV = $parentDir;
            }
            
            if(is_string($dir) && $dir != 'dir_files') {

                if(is_null($parentKey)) {
                    $parentKeyV = $dir;
                    $parentDirV = $dir;

                    if($this->isApiTransformersFolder($dir)) {
                        if($this->isApiIncluded()) {
                            $this->makeDir($this->path . '/' . $dir);
                        }
                    } else {
                        $this->makeDir($this->path . '/' . $dir);
                    }
                    
                } else {
                    $pth = $parentKey . '/' . $dir;
                    $parentKeyV = $pth;
                    $parentDirV = $pth;

                    if($this->isApiControllerFolders($pth)) {
                        if($this->isApiIncluded()) {
                            $this->makeDir($this->path . '/' . $pth);
                        }
                    } elseif($this->isUserFolders($pth)) {
                        if($this->isUser()) {
                            $this->makeDir($this->path . '/' . $pth);
                        }
                    } elseif($this->isAdminFolders($pth)) {
                        if($this->isAdmin()) {
                            $this->makeDir($this->path . '/' . $pth);
                        }
                    } else {
                        $this->makeDir($this->path . '/' . $pth);
                    }
                    
                }

                if(is_array($subDirs) && count($subDirs)) {
                    $this->generate($subDirs, $parentKeyV, $parentDirV);
                }
                
            } else {

                if(is_array($subDirs) && count($subDirs)) {
                    $pth = $this->path . '/' . $parentDirV;

                    foreach($subDirs as $file) {
                        if($this->isSoftDeletesStub($file['stub_path'])) {
                            if($this->isSoftDeletesIncluded($file['stub_path'])) {
                                $this->saveTemplate($pth, $file);
                            }
                        } elseif($this->isCreateViewStub($file['stub_path'])) {
                            if($this->isCreateViewIncluded($file['stub_path'])) {
                                $this->saveTemplate($pth, $file);
                            }
                        } elseif($this->isEditViewStub($file['stub_path'])) {
                            if($this->isEditViewIncluded($file['stub_path'])) {
                                $this->saveTemplate($pth, $file);
                            }
                        } elseif($this->isShowViewStub($file['stub_path'])) {
                            if($this->isShowViewIncluded($file['stub_path'])) {
                                $this->saveTemplate($pth, $file);
                            }
                        } elseif($this->isDeleteActionStub($file['stub_path'])) {
                            if($this->isDeleteActionIncluded($file['stub_path'])) {
                                $this->saveTemplate($pth, $file);
                            }
                        } elseif($this->isUploadActionStub($file['stub_path'])) {
                            if($this->isUploadActionIncluded($file['stub_path'])) {
                                $this->saveTemplate($pth, $file);
                            }
                        } elseif($this->isApiStub($file['stub_path'])) {
                            if($this->isApiIncluded()) {
                                $this->saveTemplate($pth, $file);
                            }
                        } else {
                            $this->saveTemplate($pth, $file);
                        }
                    }
                }
            }
        }
    }

    /**
     * Handle saving template
     * 
     * @param string $pth
     * @param string $file
     * 
     * @return void
     */
    protected function saveTemplate($pth, $file) 
    {
        $type = isset($file['type']) ? $file['type'] : ''; 

        if(in_array($type, CrudTypes::lists())) {
            if($type == 'admin' && $this->isAdmin()) {
                $this->executeSaveTemplate($pth, $file, $type);
            }
            
            if($type == 'user' && $this->isUser()) {
                $this->executeSaveTemplate($pth, $file, $type);
            }
        } else {
            $this->executeSaveTemplate($pth, $file, $type);
        }
    }

    /**
     * Handle executing on saving template
     * 
     * @param string $pth
     * @param string $file
     * @param string $type
     * 
     * @return void
     */
    protected function executeSaveTemplate($pth, $file, $type) 
    {
        $stub = base_path() . '/Modules/Modules/Stubs/DefaultTemplate/' . $file['stub_path'];
        
        $this->filesystem->copy(
            $stub, 
            $pth . '/' . str_replace('.stub', '', $this->replace(basename($stub), CrudTypeReplacements::lists($type)))
        );

        $files = $this->filesystem->files($pth, false);

        foreach($files as $file) {
            $this->filesystem->put($file, $this->replace(
                $this->filesystem->get($file),
                CrudTypeReplacements::lists($type)
            ));
        }
    }

    /**
     * Handle replacing the shortcode with the value of module file content
     * 
     * @param string $content
     * @param array $extraReplacements
     * 
     * @return string
     */
    protected function replace($content, $extraReplacements) 
    {   
        $replacements = array_merge($this->replacements, $extraReplacements);

        foreach($replacements as $shortcode=>$value) {
            $content = str_replace($shortcode, $value, $content);
        }
        return $content;
    }

    /**
     * Make the directory
     * 
     * @param string $path The path to make directory
     * 
     * @return void
     */
    protected function makeDir($path) 
    {
    	if ( ! $this->filesystem->exists($path) ) {
            $this->filesystem->makeDirectory($path);
        }
    }

    /**
     * Check if stub is soft deletes
     * 
     * @param string $stub
     * 
     * @return boolean
     */
    protected function isSoftDeletesStub($stub) 
    {
        $softDeletesStubs = ['$STUDLY_NAME$Restored.php.stub', '$STUDLY_NAME$ForceDeleted.php.stub'];

        return in_array($stub, $softDeletesStubs);
    }


    /**
     * Check if soft deletes included
     * 
     * @param string $stub
     * 
     * @return boolean
     */
    protected function isSoftDeletesIncluded($stub) 
    {
        return isset($this->moduleAttributes['included']['soft_deletes']) 
            && $this->moduleAttributes['included']['soft_deletes'] == 'on';
    }

    /**
     * Check if stub is create view
     * 
     * @param string $stub
     * 
     * @return boolean
     */
    protected function isCreateViewStub($stub) 
    {
        $stubs = [
            'views/create.blade.php.stub', 
            'assets/js/$CAMEL_NAME$Create.js.stub', 
            '$STUDLY_NAME$Created.php.stub',
            'Requests/Store$MODEL$Request.php.stub'
        ];

        return in_array($stub, $stubs);
    }

    /**
     * Check if create view is included
     * 
     * @param string $stub
     * 
     * @return boolean
     */
    protected function isCreateViewIncluded($stub) 
    {
        return isset($this->moduleAttributes['included']['create_form']) 
            && $this->moduleAttributes['included']['create_form'] == 'on';
    }

    /**
     * Check if stub is edit view
     * 
     * @param string $stub
     * 
     * @return boolean
     */
    protected function isEditViewStub($stub) 
    {
        $stubs = [
            'views/edit.blade.php.stub', 
            'assets/js/$CAMEL_NAME$Update.js.stub', 
            '$STUDLY_NAME$Updated.php.stub',
            'Requests/Update$MODEL$Request.php.stub'
        ];

        return in_array($stub, $stubs);
    }

    /**
     * Check if edit view is included
     * 
     * @param string $stub
     * 
     * @return boolean
     */
    protected function isEditViewIncluded($stub) 
    {
        return isset($this->moduleAttributes['included']['edit_form']) 
            && $this->moduleAttributes['included']['edit_form'] == 'on';
    }

    /**
     * Check if stub is edit view
     * 
     * @param string $stub
     * 
     * @return boolean
     */
    protected function isShowViewStub($stub) 
    {
        $stubs = [
            'views/show.blade.php.stub'
        ];

        return in_array($stub, $stubs);
    }

    /**
     * Check if edit view is included
     * 
     * @param string $stub
     * 
     * @return boolean
     */
    protected function isShowViewIncluded($stub) 
    {
        return isset($this->moduleAttributes['included']['show_page']) 
            && $this->moduleAttributes['included']['show_page'] == 'on';
    }

    /**
     * Check if stub is delete action
     * 
     * @param string $stub
     * 
     * @return boolean
     */
    protected function isDeleteActionStub($stub) 
    {
        $stubs = [
            'assets/js/$CAMEL_NAME$Delete.js.stub'
        ];

        return in_array($stub, $stubs);
    }

    /**
     * Check if delete action is included
     * 
     * @param string $stub
     * 
     * @return boolean
     */
    protected function isDeleteActionIncluded($stub) 
    {
        return isset($this->moduleAttributes['included']['delete_action']) 
            && $this->moduleAttributes['included']['delete_action'] == 'on';
    }

    /**
     * Check if stub has upload action
     * 
     * @param string $stub
     * 
     * @return boolean
     */
    protected function isUploadActionStub($stub) 
    {
        $stubs = [
            'assets/js/$CAMEL_NAME$Upload.js.stub'
        ];

        return in_array($stub, $stubs);
    }

    /**
     * Check if delete action is included
     * 
     * @param string $stub
     * 
     * @return boolean
     */
    protected function isUploadActionIncluded($stub) 
    {
        $uploads = ['photo', 'file'];


        foreach($this->moduleAttributes['fields'] as $field) {
            $field = json_decode($field,true);

            $type = (new ModuleGeneratorHelper)
                ->getValueByKey($field, 'field_type');

            if(in_array($type, $uploads)) {
                return true;
            }
        }
    }

    /**
     * Check if api stub
     * 
     * @param string $stub
     * 
     * @return boolean
     */
    protected function isApiStub($stub) 
    {
        $stubs = [
            'Api/V1/$STUDLY_NAME$Controller.php.stub',
            'Transformers/$STUDLY_NAME$Resource.php.stub',
            'Requests/ApiStore$MODEL$Request.php.stub',
            'Requests/ApiUpdate$MODEL$Request.php.stub'
        ];

        return in_array($stub, $stubs);
    }

    /**
     * Check if api folders
     * 
     * @param string $folder
     * 
     * @return boolean
     */
    protected function isApiControllerFolders($folder) 
    {
        $folders = [
            'Http/Controllers/Api',
            'Http/Controllers/Api/V1'
        ];

        return in_array($folder, $folders);
    }

    /**
     * Check if user folders
     * 
     * @param string $folder
     * 
     * @return boolean
     */
    protected function isUserFolders($folder) 
    {
        $folders = [
            'Http/Controllers/Web/User',
            'Resources/views/user'
        ];

        return in_array($folder, $folders);
    }

    /**
     * Check if admin folders
     * 
     * @param string $folder
     * 
     * @return boolean
     */
    protected function isAdminFolders($folder) 
    {
        $folders = [
            'Http/Controllers/Web/Admin',
            'Resources/views/admin'
        ];

        return in_array($folder, $folders);
    }

    /**
     * Check if api transformer folder
     * 
     * @param string $folder
     * 
     * @return boolean
     */
    protected function isApiTransformersFolder($folder) 
    {
        $folders = [
            'Transformers'
        ];

        return in_array($folder, $folders);
    }

    /**
     * Check if api is included
     * 
     * @return boolean
     */
    protected function isApiIncluded() 
    {
        return isset($this->moduleAttributes['included']['generate_api_crud']) 
            && $this->moduleAttributes['included']['generate_api_crud'] == 'on';
        
    }

    /**
     * Check if admin is included
     * 
     * @param string $stub
     * 
     * @return boolean
     */
    protected function isAdmin() 
    {
        return isset($this->moduleAttributes['included']['admin']) 
            && $this->moduleAttributes['included']['admin'] == 'on';
    }

    /**
     * Check if user is included
     * 
     * @param string $stub
     * 
     * @return boolean
     */
    protected function isUser() 
    {
        return isset($this->moduleAttributes['included']['user']) 
            && $this->moduleAttributes['included']['user'] == 'on';
    }
}