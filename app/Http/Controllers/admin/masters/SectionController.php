<?php

namespace App\Http\Controllers\admin\masters;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Section;
use App\Models\LogsSection;

class SectionController extends Controller
{
    public function index(){
        $moduleName = "Section List";
        $section_list = Section::orderBy('id', 'desc')->get();
        return view('admin.section.index', compact('moduleName','section_list'));
    }

    public function create(){
        $moduleName = "Create Section";
        return view('admin.section.create', compact('moduleName'));
    }

    public function store(Request $request){
        $request->validate([
            'section_name' => 'required|string',
        ]);
        if (Section::where('section_name', $request->section_name)->exists()) {
            return redirect()->route('admin.section.create')
                ->with('error', 'Section already exists.');
        }
        $Section = new Section;
        $Section->section_name = $request->section_name;
        $Section->save();

        LogsSection::create([
            'section_id' => $Section->id,
            'section_name' => $request->section_name,
            'creater_id' => auth()->id(),
            'creater_type' => auth()->user()->user_type,
            'message' => 'Section has been created.',
        ]);
        return redirect()->route('admin.section.index')
            ->with('success', 'Section added successfully.');
    }

    public function edit($id){
        $moduleName = "Edit Section";
        $editData = Section::where('id', $id)->get()->first();
        return view('admin.section.edit', ['editData' => $editData, 'moduleName' => $moduleName]);
    }

    public function update(Request $request){
            $request->validate([
                'section_name' => 'required|string',
            ]);
            $section = Section::find($request->section_id);

            if ($section) {
                $oldName = $section->section_name;


                $section->update([
                    'section_name' => $request->section_name
                ]);

                LogsSection::create([
                    'section_id'   => $request->section_id,
                    'section_name'   => $request->section_name,
                    'creater_id'      => auth()->id(),
                    'creater_type'    => auth()->user()->user_type,
                    'message'         => "Section has been updated from '$oldName' to '{$request->section_name}'.",
                ]);
            }
            return redirect()->route('admin.section.index')
            ->with('success', 'Section Updated successfully.');
    }

    public function destroy(Request $request){
        $section = Section::find($request->delete_id);

        if ($section) {
            $sectionName = $section->section_name;
            $section->delete();

            LogsSection::create([
                'section_id'   => $request->delete_id,
                'section_name' => $sectionName,
                'creater_id' => auth()->id(),
                'creater_type' => auth()->user()->user_type,
                'message' => 'Section has been deleted.',
            ]);
        }

        return redirect()->route('admin.section.index')
        ->with('success', 'Section Deleted successfully.');
    }

}
