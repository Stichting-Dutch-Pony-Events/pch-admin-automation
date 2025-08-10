<?php

namespace App\DataAccessLayer\EBoekHouden\Repository;

use App\DataAccessLayer\EBoekHouden\FilterParams\StringFilter;
use App\DataAccessLayer\EBoekHouden\Request\RelationRequest;
use App\DataAccessLayer\EBoekHouden\Responses\RelationCreatedResponse;
use App\DataAccessLayer\EBoekHouden\Responses\RelationListResponse;
use App\DataAccessLayer\EBoekHouden\Views\Relation;
use App\Util\Exceptions\Exception\Common\InvalidApiResponseException;
use App\Util\Exceptions\Exception\Entity\EntityNotFoundException;

class RelationRepository extends BaseRepository
{
    public function retrieveByEmail(string $email): Relation
    {
        $relationListResponse = $this->retrieveMany('v1/relation', RelationListResponse::class, [
            new StringFilter('email', strtolower($email)),
        ]);

        if (!$relationListResponse instanceof RelationListResponse) {
            throw new InvalidApiResponseException('Invalid response type for relation retrieval by email.');
        }

        if ($relationListResponse->count === 0) {
            throw new EntityNotFoundException('Relation not found for email: '.$email);
        }

        return $relationListResponse->items[0];
    }

    public function retrieveById(int $id): Relation
    {
        $relation = $this->retrieveOne('v1/relation/'.$id, Relation::class);

        if (!$relation instanceof Relation) {
            throw new InvalidApiResponseException('Invalid response type for relation retrieval by ID.');
        }

        return $relation;
    }

    /**
     * @throws InvalidApiResponseException
     */
    public function createRelation(RelationRequest $relationRequest): Relation
    {
        $relationCreatedResponse = $this->create('v1/relation', $relationRequest, RelationCreatedResponse::class);
        if (!$relationCreatedResponse instanceof RelationCreatedResponse) {
            throw new InvalidApiResponseException('Invalid response type for relation creation.');
        }

        return $this->retrieveById($relationCreatedResponse->id);
    }

    public function patchRelation(Relation $relation, RelationRequest $relationRequest): Relation
    {
        $url = 'v1/relation/'.$relation->id;

        $response = $this->patch($url, $relationRequest, Relation::class);
        if (!$response) {
            throw new InvalidApiResponseException('Failed to update relation with ID: '.$relation->id);
        }

        return $this->retrieveById($relation->id);
    }
}
