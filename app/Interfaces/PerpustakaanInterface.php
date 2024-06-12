<?php

namespace App\Interfaces;

interface PerpustakaanInterface
{
    public function add(array $data); // CREATE
    public function update($id, array $data); // UPDATE
    public function destroy($id); // DELETE
    public function findAll($sortBy, $orderBy); // READ ALL
    public function findById($id); // READ BY ID
}
