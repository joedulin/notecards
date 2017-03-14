<?php

class MAController extends BaseController {
	public function __construct($f3=array(), $params=array()) {
		parent::__construct($f3, $params);
	}

	public function addresses_page() {
		$this->pageheader = 'Multialert';
		$this->pagesubheader = 'Addresses';
		$this->pageRender('ma/manage_addresses');
	}

	//ajax -----------------------------------------------------------------------
	public function create_group() {
		$group_name = $this->required('group_name');
		$name = $this->required('name');
		$address = $this->required('address');
		$unit_type = $this->required('unit_type');
		$unit_number = $this->required('unit_number');
		$city = $this->required('city');
		$state = $this->required('state');
		$zip = $this->required('zip');

		$group = MaGroups::select(array(
			'user_id' => $this->user->id,
			'group_name' => $group_name
		));
		if (!empty($group)) {
			return $this->res(400, 'error', 'You already have a group with this name. Please select a different name');
		}
		$group = new MaGroups();
		$group->user_id = $this->user->id;
		$group->group_name = $group_name;
		$group->e_name = $name;
		$group->e_address = $address;
		$group->e_unit_type = ($unit_type == '0') ? 'none' : $unit_type;
		$group->e_unit_number = ($unit_number == '0') ? 'none' : $unit_number;
		$group->e_city = $city;
		$group->e_state = $state;
		$group->e_zip = $zip;

		if ($group->save()) {
			return $this->res(200, 'success', $group->clean());
		}
		return $this->res(500, 'error', 'Could not create group');
	}

	public function list_groups() {
		$limit = $this->get('limit', 100);
		$offset = $this->get('offset', 0);
		$limit = preg_replace('/[^0-9]/', '', $limit);
		$offset = preg_replace('/[^0-9]/', '', $offset);
		$search = $this->get('search');

		if ($search) {
			$search = sprintf('%%%s%%', $search);
			$query = sprintf("SELECT * FROM ma_groups WHERE user_id = ? AND (group_name LIKE ? OR e_address LIKE ? OR e_city LIKE ? OR e_state LIKE ? OR e_zip LIKE ?) LIMIT %s OFFSET %s", $limit, $offset);
			$groups = MaGroups::query($query, array( $this->user->id, $search, $search, $search, $search, $search ));
		} else {
			$groups = MaGroups::select(array(
				'user_id' => $this->user->id,
				'limit' => $limit,
				'offset' => $offset
			));
		}
		return $this->res(200, 'success', $groups);
	}

	public function get_group() {
		$group_id = $this->get('group_id');
		$group_name = $this->get('group_name');
		if ($group_id) {
			$group = MaGroups::select(array(
				'id' => $group_id,
				'user_id' => $this->user->id
			));
		} else if ($group_name) {
			$group = MaGroups::select(array(
				'user_id' => $this->user->id,
				'group_name' => $group_name
			));
		} else {
			return $this->res(400, 'error', 'Please provide group_id OR group_name');
		}
		if (empty($group)) {
			return $this->res(400, 'error', 'MA Address not found');
		}
		$group = $group[0];
		$group->numbers = Dids::select(array(
			'user_id' => $this->user->id,
			'group_id' => $group->id
		));
		return $this->res(200, 'success', $group);
	}

	public function modify_group() {
		$group_id = $this->get('group_id');
		$group_name = $this->get('group_name');
		$name = $this->get('name');
		$address = $this->get('address');
		$unit_type = $this->get('unit_type');
		$unit_number = $this->get('unit_number');
		$city = $this->get('city');
		$state = $this->get('state');
		$zip = $this->get('zip');

		if ($group_id) {
			$group = MaGroups::select(array(
				'id' => $group_id,
				'user_id' => $this->user->id
			));
		} else if ($group_name) {
			$group = MaGroups::select(array(
				'user_id' => $this->user->id,
				'group_name' => $group_name
			));
		} else {
			return $this->res(400, 'error', 'Please provide group_id OR group_name');
		}
		if (empty($group)) {
			return $this->res(400, 'error', 'Group not found');
		}
		$group = $group[0];

		$group->group_name = ($group_name) ? $group_name : $group->group_name;
		$group->e_name = ($name) ? $name : $group->e_name;
		$group->e_address = ($address) ? $address : $group->e_address;
		$group->e_unit_type = ($unit_type) ? $unit_type : $group->e_unit_type;
		$group->e_unit_number = ($unit_number) ? $unit_number : $group->e_unit_number;
		$group->e_city = ($city) ? $city : $group->e_city;
		$group->e_state = ($state) ? $state : $group->e_state;
		$group->e_zip = ($zip) ? $zip : $group->e_zip;

		if ($group->save()) {
			$dids = Dids::select('group_id', $group->id);
			foreach ($dids as $did) {
				$q = new AlterNumberAddressQueue();
				$q->user_id = $this->user->id;
				$q->did_id = $did->id;
				$q->process_status = 'new';
				$q->save();
			}
			return $this->res(200, 'success', 'Successfully modified address. Please allow a few minutes for changes to take place');
		}
		return $this->res(500, 'error', 'Could not modify address');
	}

	public function remove_group() {
		$group_id = $this->get('group_id');
		$group_name = $this->get('group_name');
		$move_group_id = $this->get('move_group_id');
		$move_group_name = $this->get('move_group_name');

		if ($group_id) {
			$group = MaGroups::select(array(
				'id' => $group_id,
				'user_id' => $this->user->id
			));
		} else if ($group_name) {
			$group = MaGroups::select(array(
				'user_id' => $this->user->id,
				'group_name' => $group_name
			));
		}
		if (empty($group)) {
			return $this->res(400, 'error', 'Address not found');
		}
		$group = new MaGroups($group[0]);
		$dids = Dids::select(array(
			'group_id' => $group->id
		));

		if ($move_group_name || $move_group_id) {
			if ($move_group_id) {
				$move_group = MaGroups::select(array(
					'id' => $move_group_id,
					'user_id' => $this->user->id
				));
			} else if ($move_group_name) {
				$move_group = MaGroups::select(array(
					'user_id' => $this->user->id,
					'grouo_name' => $move_group_name
				));
			}
			if (empty($move_group)) {
				return $this->res(400, 'error', 'Could not find the gorup specified to move to');
			}
			$move_group = $move_grouo[0];
			foreach ($dids as $did) {
				$did = new Dids($did);
				$did->group_id = $move_group[0]->id;
				if ($did->save()) {
					$q = new AlterNumberAddressQueue();
					$q->user_id = $this->user->id;
					$q->did_id = $did->id;
					$q->process_status = 'new';
					$q->save();
				}
			}
		} else {
			foreach ($dids as $did) {
				$q = new RemoveNumberQueue();
				$q->user_id = $this->user->id;
				$q->did_id = $did->id;
				$q->process_status = 'new';
				$q->save();
			}
		}

		if ($group->deleteRow()) {
			return $this->res(200, 'success', 'Successfully removed address');
		}
		return $this->res(500, 'error', 'Could not remove address');
	}
}
