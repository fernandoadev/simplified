<?php

use App\Helpers\DocumentHelper;
use App\Exceptions\InvalidDocumentException;

$validCpfValuesWithoutMask = [
    '87458783079',
    '12345678909',
    '13778305000',
    '41772833045'
];

$validCnpjValuesWithoutMask = [
    '93922925000170',
    '46194011000150',
    '72500597000144',
    '12525484000171'
];


describe('Create valid document', function () use ($validCpfValuesWithoutMask, $validCnpjValuesWithoutMask) {
    it('Valid CNPJ without special char document', function () use ($validCnpjValuesWithoutMask) {
        foreach ($validCnpjValuesWithoutMask as $cnpj) {
            $document = DocumentHelper::fromString($cnpj);
            expect($document)->toBeInstanceOf(DocumentHelper::class);
        }
    });

    it('Valid CNPJ with special char document', function () use ($validCnpjValuesWithoutMask) {
        foreach ($validCnpjValuesWithoutMask as $cnpj) {
            $documentWithMask = DocumentHelper::cnpjWithMask($cnpj);
            $document = DocumentHelper::fromString($documentWithMask);
            expect($document)->toBeInstanceOf(DocumentHelper::class);
        }
    });

    it('Valid CPF without special char document', function () use ($validCpfValuesWithoutMask) {
        foreach ($validCpfValuesWithoutMask as $cpf) {
            $document = DocumentHelper::fromString($cpf);
            expect($document)->toBeInstanceOf(DocumentHelper::class);
        }
    });

    it('Valid CPF with special char document', function ()  use ($validCpfValuesWithoutMask) {
        foreach ($validCpfValuesWithoutMask as $cpf) {
            $documentWithMask = DocumentHelper::cpfWithMask($cpf);
            $document = DocumentHelper::fromString($documentWithMask);
            expect($document)->toBeInstanceOf(DocumentHelper::class);
        }
    });
});

describe('Should throw InvalidDocumentException', function () {
    it('When CPF has the wrong length', function () {
        $invalidCpf = '0123456789';

        expect(fn() => DocumentHelper::fromString($invalidCpf))
            ->toThrow(InvalidDocumentException::class);
    });

    it('When CNPJ has the wrong length', function () {
        $invalidCpf = '2345678921321';

        expect(fn() => DocumentHelper::fromString($invalidCpf))
            ->toThrow(InvalidDocumentException::class);
    });
});
