<?php
namespace Oreilly\ModernPHP\Url;
class Scanner
{
  /**
   * @var array Массив Url-адрессов
   */
  protected $urls;

  /**
   * @var \GuzzleHttp\Client
   */
  protected $httpClient;

  /**
   * Конструктор
   * @param array $urls Массив Url-адресов для сканирования
   */
  public function __construct(array $urls)
  {
    $this->urls = $urls;
    $this->httpClient = new \GuzzleHttp\Client();
  }

  /**
   * Возращает недопустимые Url-адреса
   */
  public function getInvalidUrls()
  {
    $invalidUrls = [];
    foreach ($this->urls as $url):
      try {
        $statusCode = $this->getStatusCodeForUrl($url);
      } catch (\Exception $e) {
        $statusCode = 500;
      }
      if ($statusCode >= 400):
        array_push($invalidUrls, [
          'url' => $url,
          'status' => $statusCode
        ]);
      endif;
    endforeach;
    return $invalidUrls;
  }

  /**
   * Возращает код состояния HTTP Url-адреса
   * @param string $url Удаленный Url-адрес
   * @return int Код состояния HTTP
   */
  protected function getStatusCodeForUrl($url)
  {
    $httpResponse = $this->httpClient->request('GET', $url);
    return $httpResponse->getStatusCode();
  }
}
