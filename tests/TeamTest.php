<?php
class TeamTest extends TestCase {
	/**
	 * /teams [GET]
	 */
	public function testShouldReturnAllteams() {
		$this->get("teams", []);
		$this->seeStatusCode(200);
		$this->seeJsonStructure([
			'success' => true,
			'teams' => ['*' =>
				[
					'id',
					'name',
					'is_active',
					'created_at',
					'updated_at',
					'user',
				],
			],
		]);

	}
	/**
	 * /teams/id [GET]
	 */
	public function testShouldReturnTeam() {
		$this->get("products/1", []);
		$this->seeStatusCode(200);
		$this->seeJsonStructure(
			['user' =>
				[
					'id',
					'name',
					'is_active',
					'created_at',
					'updated_at',
					'user',
				],
			]
		);

	}
	/**
	 * /teams [POST]
	 */
	public function testShouldCreateTeam() {
		$parameters = [
			'name' => 'Test Team',
		];
		$this->post("teams", $parameters, []);
		$this->seeStatusCode(200);
		$this->seeJsonStructure(
			[
				'success',
				'error',
			]
		);

	}

	/**
	 * /teams/id [PUT]
	 */
	public function testShouldUpdateTeam() {
		$parameters = [
			'name' => 'Test Team',
		];
		$this->put("teams/4", $parameters, []);
		$this->seeStatusCode(200);
		$this->seeJsonStructure(
			[
				'success',
				'error',
			]
		);
	}
	/**
	 * /teams/id [DELETE]
	 */
	public function testShouldDeleteTeam() {

		$this->delete("teams/5", [], []);
		$this->seeStatusCode(410);
		$this->seeJsonStructure([
			'success',
			'error',
		]);
	}
}