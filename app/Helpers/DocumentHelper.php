<?php

namespace App\Helpers;

use App\Exceptions\InvalidDocumentException;

class DocumentHelper
{
    private string $document;

    public const CPF = 'cpf';
    public const CNPJ = 'cnpj';

    private function __construct(string $document)
    {
        $this->document = $document;
    }

    /**
     * @param string $document
     * 
     * @return DocumentHelper
     * 
     * @throws InvalidDocumentException
     */
    public static function fromString(string $document): self
    {
        $document = self::onlyNumbers($document);

        self::isValidDocument($document);

        return new self($document);
    }

    /**
     * @return string
     */
    public function toString(): string
    {
        return $this->document;
    }

    /**
     * @param string $cnpj
     * 
     * @return string
     * 
     * @throws InvalidDocumentException
     */
    public static function CnpjWithMask(string $cnpj): string
    {
        $cnpj = self::onlyNumbers($cnpj);

        if (strlen($cnpj) !== 14) {
            throw InvalidDocumentException::withDocument($cnpj);
        }

        return preg_replace(
            '/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/',
            '$1.$2.$3/$4-$5',
            $cnpj
        );
    }

    /**
     * @param string $cpf
     * 
     * @return string
     * 
     * @throws InvalidDocumentException
     */
    public static function cpfWithMask(string $cpf): string
    {
        $cpf = self::onlyNumbers($cpf);

        if (strlen($cpf) !== 11) {
            throw InvalidDocumentException::withDocument($cpf);
        }

        return preg_replace(
            '/^(\d{3})(\d{3})(\d{3})(\d{2})$/',
            '$1.$2.$3-$4',
            $cpf
        );
    }

    /**
     * @param string $document
     * 
     * @return bool
     * 
     * @throws InvalidDocumentException
     */
    private static function isValidDocument(string $document): bool
    {
        $document = self::onlyNumbers($document);

        if (strlen($document) == 11) {
            return true;
        }

        if (strlen($document) == 14) {
            return true;
        }

        throw InvalidDocumentException::withDocument($document);
    }

    /**
     * @param string $str
     * 
     * @return string
     */
    private static function onlyNumbers(string $str): string
    {
        return preg_replace('#\D+#', '', $str);
    }
}
