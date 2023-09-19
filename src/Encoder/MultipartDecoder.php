<?php

namespace App\Encoder;


use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

final class MultipartDecoder implements DecoderInterface
{
    public const FORMAT = 'multipart';

    public function __construct(private RequestStack $requestStack)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function decode(string $data, string $format, array $context = []): ?array
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$request) {
            return null;
        }

        
      

        $request_data = $request->request->all();

        
        
        if (isset($request_data["annee"])) $request_data["annee"] = intval($request_data["annee"]);
        if (isset($request_data["jouers"])) $request_data["jouers"] = intval($request_data["jouers"]);
        if (isset($request_data["isExtension"])) $request_data["isExtension"] = $request_data["isExtension"] === "true";
        if (isset($request_data["bloquer"])) $request_data["bloquer"] = $request_data["bloquer"] === "true";

        if (isset($request_data["langues"])) {
            $data = explode(',', $request_data["langues"]);
            if (count($data) > 0 && $request_data["langues"] !== "") {
                $request_data["langues"] = explode(',', $request_data["langues"]);
            } else if ($request_data["langues"] === "") {
                $request_data["langues"] = [];
            }
        }

        if (isset($request_data["mecanismes"])) {
            $data = explode(',', $request_data["mecanismes"]);
            if (count($data) > 0 && $request_data["mecanismes"] !== "") {
                $request_data["mecanismes"] = explode(',', $request_data["mecanismes"]);
            } else if ($request_data["mecanismes"] === "") {
                $request_data["mecanismes"] = [];
            }
        }

       
         return $request_data + $request->files->all();
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDecoding(string $format): bool
    {
        return self::FORMAT === $format;
    }
}
