<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App;

use Carbon\Carbon;
use Illuminate\Auth\Passwords\DatabaseTokenRepository;

/**
 * Description of ActivationRepository
 * User activation class
 * @author Ilya
 */
class ActivationRepository {

    protected $db;
    protected $table = 'user_activations';

    public function __construct(Connection $db) {
        $this->db = $db;
    }

    protected function getToken() {
        return hash_hmac('sha256', str_random(40), config('app.key'));
    }

    public function createActivation($user) {

        $activation = $this->getActivation($user);

        if (!$activation) {
            return $this->createToken($user);
        }
        return $this->regenerateToken($user);
    }

    private function regenerateToken($user) {

        $token = $this->getToken();
        $this->db->table($table)->where('user_id', $user->id)->update([
            'token' => $token,
            'created_at' => new Carbon()
        ]);
        return $token;
    }

    private function createToken($user) {
        $token = $this->getToken();
        $this->db->table($this->table)->insert([
            'user_id' => $user->id,
            'token' => $token,
            'created_at' => new Carbon()
        ]);
        return $token;
    }

    public function getActivation($user) {
        return $this->db->table($this->table)->where('user_id', $user->id)->first();
    }

    public function getActivationByToken($token) {
        return $this->db->table($this->table)->where('token', $token)->first();
    }

    public function deleteActivation($token) {
        $this->db->table($this->table)->where('token', $token)->delete();
    }

}
