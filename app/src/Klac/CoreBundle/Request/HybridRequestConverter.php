<?php

namespace Klac\CoreBundle\Request;

use FOS\RestBundle\Request\RequestBodyParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class HybridRequestConverter extends RequestBodyParamConverter
{
    /**
     * {@inheritdoc}
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $this->reconfigureRequest($request, $configuration);
        parent::apply($request, $configuration);
    }

    /**
     * @param Request $request
     * @param ParamConverter $configuration
     *
     * @return mixed
     */
    protected function reconfigureRequest(Request $request, ParamConverter $configuration)
    {
        $jsonData = $request->getContent();
        $dataArray = json_decode($jsonData, true);

        // Modify Data
        $id = $request->attributes->get('id');
        $dataArray['id'] = $id;

        if(isset($configuration->getOptions()['entity_type'])) {
            $dataArray['entity_type'] = $configuration->getOptions()['entity_type'];
        }

        $jsonData = json_encode($dataArray);

        // cloning request with new content. This is made to inject resource-id from URI into content for making api using services easier
        $request->initialize(
            $request->query->all(),
            $request->request->all(),
            $request->attributes->all(),
            $request->cookies->all(),
            $request->files->all(),
            $request->server->all(),
            $jsonData
        );

        return $request;
    }
}