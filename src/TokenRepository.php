<?php

namespace Whitehatsllc\PassportMongodb;

use Carbon\Carbon;
use Jenssegers\Mongodb\Eloquent\Model;

class TokenRepository
{

    /**
     * Creates a new Access Token.
     *
     * @param  array  $attributes
     * @return \Whitehatsllc\PassportMongodb\Token
     */
    public function create($attributes) {
        return Token::create($attributes);
    }

    /**
     * Get a token by the given ID.
     *
     * @param  string  $id
     * @return \Whitehatsllc\PassportMongodb\Token
     */
    public function find($id) {
        return Token::find(['id' => $id]);
    }

    /**
     * Get a token by the given ID.
     *
     * @param  string  $id
     * @return \Whitehatsllc\PassportMongodb\Token
     */
    public function getTokenById($id) {
        return Token::where(['id' => $id])->first()->attributesToArray();
    }

    /**
     * Get a token by the given user ID and token ID.
     *
     * @param  string  $id
     * @param  int  $userId
     * @return \Whitehatsllc\PassportMongodb\Token|null
     */
    public function findForUser($id, $userId) {
        return Token::where('id', $id)->where('user_id', $userId)->first();
    }

    /**
     * Get the token instances for the given user ID.
     *
     * @param  mixed  $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function forUser($userId) {
        return Token::where('user_id', $userId)->get();
    }

    /**
     * Get a valid token instance for the given user and client.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $user
     * @param  \Whitehatsllc\PassportMongodb\Client  $client
     * @return \Whitehatsllc\PassportMongodb\Token|null
     */
    public function getValidToken($user, $client) {
        return $client->tokens()
                        ->whereUserId($user->getKey())
                        ->whereRevoked(0)
                        ->where('expires_at', '>', Carbon::now())
                        ->first();
    }

    /**
     * Store the given token instance.
     *
     * @param  \Whitehatsllc\PassportMongodb\Token  $token
     * @return void
     */
    public function save(Token $token) {
        $token->save();
    }

    /**
     * Revoke an access token.
     *
     * @param  string  $id
     * @return mixed
     */
    public function revokeAccessToken($id) {
        return Token::where('id', $id)->update(['revoked' => true]);
    }

    /**
     * Check if the access token has been revoked.
     *
     * @param  string  $id
     *
     * @return bool Return true if this token has been revoked
     */
    public function isAccessTokenRevoked($id) {
        if ($token = Token::where(['id' => $id])->first()->toArray()) {
            if (isset($token['revoked']) && $token['revoked'] == 0)
                return false;
        }
        return true;
    }

    /**
     * Find a valid token for the given user and client.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $user
     * @param  \Whitehatsllc\PassportMongodb\Client  $client
     * @return \Whitehatsllc\PassportMongodb\Token|null
     */
    public function findValidToken($user, $client) {
        return $client->tokens()
                        ->whereUserId($user->getKey())
                        ->whereRevoked(0)
                        ->where('expires_at', '>', Carbon::now())
                        ->latest('expires_at')
                        ->first();
    }

}
