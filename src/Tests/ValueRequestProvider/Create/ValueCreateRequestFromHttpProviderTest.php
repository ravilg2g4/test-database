<?php

declare(strict_types=1);

namespace App\Tests\ValueRequestProvider\Create;

use App\Exceptions\DataForCRUDNotFoundException;
use App\ValueRequestProvider\Create\ValueCreateRequestFromHttpProvider;
use PHPUnit\Framework\TestCase;

class ValueCreateRequestFromHttpProviderTest extends TestCase
{
    public function testGetArrayValueRequest(): void
    {
        $expectedArray = ['name' => 'name',
                          'surname' => 'surname',
                          'email' => 'test@gmail.com'
        ];
        $dataForJson = json_encode($expectedArray);
        $this->fileGetContentsMock($dataForJson);
        $provider = new ValueCreateRequestFromHttpProvider();

        $result = $provider->getArrayValueRequest();

        $this->assertEquals($expectedArray, $result);
    }
    public function testGetNameNewUser(): void
    {
        $expectedName = 'name';
        $dataForJson = json_encode(['name' => 'name',
                                    'surname' => 'surname',
                                    'email' => 'test@gmail.com'
        ]);
        $this->fileGetContentsMock($dataForJson);
        $provider = new ValueCreateRequestFromHttpProvider();

        $result = $provider->getNameNewUser();

        $this->assertEquals($expectedName, $result);
    }
    public function testGetNameNewUserForNameNotFound(): void
    {
        $dataForJson = json_encode(['surname' => 'surname',
                                    'email' => 'test@gmail.com'
        ]);
        $this->fileGetContentsMock($dataForJson);
        $provider = new ValueCreateRequestFromHttpProvider();

        $this->expectException(DataForCRUDNotFoundException::class);

        $provider->getNameNewUser();
    }
    public function testGetNameNewUserForEmptyName(): void
    {
        $dataForJson = json_encode(['name' => '',
            'surname' => 'surname',
            'email' => 'test@gmail.com'
        ]);
        $this->fileGetContentsMock($dataForJson);
        $provider = new ValueCreateRequestFromHttpProvider();

        $this->expectException(DataForCRUDNotFoundException::class);

        $provider->getNameNewUser();
    }
    public function testGetSurnameNewUser(): void
    {
        $expectedSurname = 'surname';
        $dataForJson = json_encode(['name' => 'name',
                                    'surname' => 'surname',
                                    'email' => 'test@gmail.com'
        ]);
        $this->fileGetContentsMock($dataForJson);
        $provider = new ValueCreateRequestFromHttpProvider();

        $result = $provider->getSurnameNewUser();

        $this->assertEquals($expectedSurname, $result);
    }
    public function testGetSurnameNewUserForSurnameNotFound(): void
    {
        $dataForJson = json_encode(['name' => 'name',
                                    'email' => 'test@gmail.com'
        ]);
        $this->fileGetContentsMock($dataForJson);
        $provider = new ValueCreateRequestFromHttpProvider();

        $this->expectException(DataForCRUDNotFoundException::class);

        $provider->getSurnameNewUser();
    }
    public function testGetSurnameNewUserForEmptySurname(): void
    {
        $dataForJson = json_encode(['name' => 'name',
                                    'surname' => '',
                                    'email' => 'test@gmail.com'
        ]);
        $this->fileGetContentsMock($dataForJson);
        $provider = new ValueCreateRequestFromHttpProvider();

        $this->expectException(DataForCRUDNotFoundException::class);

        $provider->getSurnameNewUser();
    }
    public function testGetEmailNewUser(): void
    {
        $expectedEmail = 'test@gmail.com';
        $dataForJson = json_encode(['name' => 'name',
                                    'surname' => 'surname',
                                    'email' => 'test@gmail.com'
        ]);
        $this->fileGetContentsMock($dataForJson);
        $provider = new ValueCreateRequestFromHttpProvider();

        $result = $provider->getEmailNewUser();

        $this->assertEquals($expectedEmail, $result);
    }
    public function testGetEmailNewUserForEmailNotFound(): void
    {
        $dataForJson = json_encode(['name' => 'name',
                                    'surname' => 'surname'
        ]);
        $this->fileGetContentsMock($dataForJson);
        $provider = new ValueCreateRequestFromHttpProvider();

        $this->expectException(DataForCRUDNotFoundException::class);

        $provider->getEmailNewUser();
    }
    public function testGetEmailNewUserForEmptyEmail(): void
    {
        $dataForJson = json_encode(['name' => 'name',
                                    'surname' => 'surname',
                                    'email' => ''
        ]);
        $this->fileGetContentsMock($dataForJson);
        $provider = new ValueCreateRequestFromHttpProvider();

        $this->expectException(DataForCRUDNotFoundException::class);

        $provider->getEmailNewUser();
    }
    private function fileGetContentsMock(string $json): void
    {
        global $fileGetContents;
        $fileGetContents = $json;

        if (!function_exists('App\ValueRequestProvider\Create\file_get_contents')) {
            eval('namespace App\ValueRequestProvider\Create;
                function file_get_contents() {
                global $fileGetContents;
                return $fileGetContents;
            }');
        }
        unset($fileGetContents);
    }
}
