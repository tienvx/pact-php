UPGRADE FROM 8.x to 9.0
=======================

* Interaction Builder
  * It's now required to call `PhpPact\Consumer\InteractionBuilder::createMockServer` manually

   Example Usage:
   ```php
   $builder = new InteractionBuilder($config);
   $builder
       ->given('a person exists')
       ->uponReceiving('a get request to /hello/{name}')
       ->with($request)
       ->willRespondWith($response);
   $builder->createMockServer();
   $apiClient->sendRequest();
   $this->assertTrue($builder->verify());
   ```

* Verifier
  * Different pacts sources can be configured via `addXxx` methods

   Example Usage:
   ```php
   $config = new VerifierConfig();
   $config
       ->setPort(8000)
       ->setProviderName('someProvider')
       ->setProviderVersion('1.0.0');

   $url = new Url();
   $url
       ->setUrl(new Uri('http://localhost'))
       ->setProviderName('someProvider')
       ->setUsername('user')
       ->setPassword('pass')
       ->setToken('token');

   $selectors = (new ConsumerVersionSelectors())
       ->addSelector('{"tag":"foo","latest":true}')
       ->addSelector('{"tag":"bar","latest":true}');

   $broker = new Broker();
   $broker
       ->setUrl(new Uri('http://localhost'))
       ->setProviderName('someProvider')
       ->setUsername('user')
       ->setPassword('pass')
       ->setToken('token')
       ->setConsumerVersionSelectors($selectors);

   $verifier = new Verifier($config);
   $verifier->addFile('C:\SomePath\consumer-provider.json');
   $verifier->addDirectory('C:\OtherPath');
   $verifier->addUrl($url);
   $verifier->addBroker($broker);

   $this->assertTrue($verifier->verify());
   ```

