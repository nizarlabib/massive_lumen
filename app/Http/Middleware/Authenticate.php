<?php
namespace App\Http\Middleware;

use App\Models\User;
use Closure;

    class Authorization
    {
        /**
        * Handle an incoming request.
        *
        * @param \Illuminate\Http\Request $request
        * @param \Closure $next
        * @return mixed
        */
       public function handle($request, Closure $next)
    {

      $token = $request->header('token') ?? $request->query('token');
      if (!$token) {
          return response()->json([
              'status' => 'Error',
              'message' => 'token not provided',
          ],400);
      }

      //
      [
          $header_base64url,
          $payload_base64url,
          $signature_base64url
      ] = explode('.', $token);

      $header = $this->base64url_decode($header_base64url);
      $json_header = json_decode($header);

      if (!$json_header->alg || $json_header->alg !== 'HS256') {
          return response()->json([
              'status' => 'Error',
              'message' => 'type of token not valid',
          ],401);
      }

      if (!$json_header->typ || $json_header->typ !== 'JWT') {
          return response()->json([
              'status' => 'Error',
              'message' => 'type of token not valid',
          ],401);
      }

      $payload = $this->base64url_decode($payload_base64url);
      $json_payload = json_decode($payload);
      if (!$json_payload->id) {
        return response()->json([
          'status' => 'Error',
          'message' => 'invalid token',
      ],400);;
      }

      $verified = $this->verify($signature_base64url, $header_base64url, $payload_base64url, 'secret');
      if (!$verified) {
        return response()->json([
          'status' => 'Error',
          'message' => 'invalid sign token',
      ],400);;


      }
      $id = $json_payload->id;
      $user = User::where('id', $id)->first();
      //
      if (!$user) {
          return response()->json([
              'status' => 'Error',
              'message' => 'invalid token',
          ],400);
      }
      $request->user = $user;
      return $next($request);
    }

        private function base64url_encode(string $data): string
    {
      $base64 = base64_encode($data);
      $base64url = strtr($base64, '+/', '-_');

      return rtrim($base64url, '=');
    }

    private function base64url_decode(string $base64url): string
    {
      $base64 = strtr($base64url, '-_', '+/');
      $json = base64_decode($base64);

      return $json;
    }

    private function sign(string $header_base64url, string $payload_base64url, string $secret): string
    {
      $signature = hash_hmac('sha256', "{$header_base64url}.{$payload_base64url}", $secret, true);
      $signature_base64url = $this->base64url_encode($signature);

      return $signature_base64url;
    }


    private function verify(string $signature_base64url, string $header_base64url, string $payload_base64url, string $secret): bool
    {
      $signature = $this->base64url_decode($signature_base64url);
      $expected_signature = $this->base64url_decode($this->sign($header_base64url, $payload_base64url, $secret));

      return hash_equals($expected_signature, $signature);
    }
}