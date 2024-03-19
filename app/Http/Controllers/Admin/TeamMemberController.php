<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeamMemberRequest;
use App\Http\Services\RolePermissionService;
use App\Http\Services\TeamMemberService;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    private $teamMemberService, $rolePermissionService;

    public function __construct()
    {
        $this->teamMemberService = new TeamMemberService;
        $this->rolePermissionService = new RolePermissionService;
    }

    public function index(Request $request)
    {
        $data['pageTitle'] = __('Team Member');
        $data['activeTeamMember'] = 'active';
        $data['allTeamMemberCount'] = $this->teamMemberService->getAll()->count();
        if ($request->ajax()) {
            return $this->teamMemberService->getAllData();
        }
        return view('admin.team-member.index', $data);
    }

    public function add()
    {
        $data['pageTitleParent'] = __('Team Member');
        $data['pageTitle'] = __('Add Team Member');
        $data['activeTeamMember'] = 'active';
        $data['designations'] = $this->teamMemberService->getAllDesignation(auth()->id());
        $data['roles'] = $this->rolePermissionService->getAll();
        return view('admin.team-member.add', $data);
    }

    public function edit($id)
    {
        $data['pageTitleParent'] = __('Team Member');
        $data['pageTitle'] = __('Edit Team Member');
        $data['activeTeamMember'] = 'active';
        $data['designations'] = $this->teamMemberService->getAllDesignation(auth()->id());
        $data['teamMember'] = $this->teamMemberService->getById($id);
        $data['roles'] = $this->rolePermissionService->getAll();
        return view('admin.team-member.edit', $data);
    }

    public function store(TeamMemberRequest $request)
    {
        return $this->teamMemberService->store($request);
    }

    public function delete($id)
    {
        return $this->teamMemberService->deleteById($id);
    }
}
