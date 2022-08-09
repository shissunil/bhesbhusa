<?php



namespace App\Http\Controllers\Admin;



use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Models\DeliveryAssociate;
use App\Models\Order;
use Auth;
use Illuminate\Http\Request;



class DeliveryAssociatesController extends Controller

{

    public function __construct()

    {

        $this->middleware('auth:admin');

    }



    public function index(Request $request)
    {

        if($request->ajax()){
            $status = $request->status;
            if($status==''){
                $delivery_associates = DeliveryAssociate::where('deleted_at','')->orderBy('id','DESC')->get();
                return view('admin.delivery_associates.filter', compact('delivery_associates'));
            }else{
                $delivery_associates = DeliveryAssociate::where('deleted_at','')->where('status',$status)->orderBy('id','DESC')->get();
                return view('admin.delivery_associates.filter', compact('delivery_associates'));
            }
        }

        $delivery_associates = DeliveryAssociate::where('deleted_at','')->orderBy('id','DESC')->get();

        return view('admin.delivery_associates.index', compact('delivery_associates'));

    }
    public function create()
    {
        // $subCategoryList = SubCategory::where('deleted_at','')->where('status','1')->get();
        return view('admin.delivery_associates.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'profile_pic' => 'required',   
            'vechicle_doc' => 'required',
            'email' => 'required|email|unique:delivery_associates,email',
            'password' => 'required|min:8|max:15',
            'mobile_number' => 'required|digits:10|unique:delivery_associates,mobile_number',
            'vehicle_number' => 'required',
            'license_number' => 'required',
            'status' => 'required',
        ]);
        $password = $request->password;
        $encPassword = Crypt::encryptString($password);
        $deliveryAssociate = new DeliveryAssociate;
        $deliveryAssociate->first_name = $request->first_name;
        $deliveryAssociate->last_name = $request->last_name;
        $deliveryAssociate->email = $request->email;
        $deliveryAssociate->password = $encPassword;
        $deliveryAssociate->mobile_number = $request->mobile_number;
        $deliveryAssociate->vechicle_number = $request->vehicle_number;
        $deliveryAssociate->license_number = $request->license_number;
        $deliveryAssociate->status = $request->status;
        $deliveryAssociate->profile_pic = '';
        if ($image = $request->file('profile_pic')) 
        {
            $destinationPath = 'uploads/delivery_associates/';
            $profilePic = time() . "." . $image->getClientOriginalName();
            $image->move($destinationPath, $profilePic);
            $deliveryAssociate->profile_pic = $profilePic;
        }
        $deliveryAssociate->vechicle_doc = '';
        if ($image = $request->file('vechicle_doc')) 
        {
            $vechicleImage = 'uploads/driver_vehicle_doc/';
            $vechicleDoc = time() . "." . $image->getClientOriginalName();
            $image->move($vechicleImage, $vechicleDoc);
            $deliveryAssociate->vechicle_doc = $vechicleDoc;
        }
        $deliveryAssociate->device_type = '';
        $deliveryAssociate->device_token = '';
        $deliveryAssociate->deleted_at = '';
        $deliveryAssociate->save();
        return redirect()->route('admin.delivery_associates.index')->with('success', 'Delivery Associate Created successfully.');
    }
    public function edit($id)
    {
        $assignOrders = Order::where('deleted_at','')->where('delivery_associates_id',$id)->get();
        $user = DeliveryAssociate::findOrFail($id);
        return view('admin.delivery_associates.edit', compact('user','assignOrders'));

    }



    public function update(Request $request, $id)
    {

        $deliveryAssociate = DeliveryAssociate::findOrFail($id);
        $request->validate([
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|unique:delivery_associates,email,'.$deliveryAssociate->id,
            'mobile_number' => 'required|digits:10|unique:delivery_associates,mobile_number,'.$deliveryAssociate->id,
            'vehicle_number' => 'required',
            'license_number' => 'required',
            'status' => 'required',
        ]);

        if ($request->status == '0')
        {
            $userTokens = $deliveryAssociate->tokens;
            foreach($userTokens as $token)
            {
                $token->delete();   
            }
        }
        // $password = $request->password;
        // $encPassword = Crypt::encryptString($password);
        $deliveryAssociate->first_name = $request->first_name;
        $deliveryAssociate->last_name = $request->last_name;
        $deliveryAssociate->email = $request->email;
        // $deliveryAssociate->password = $encPassword;
        $deliveryAssociate->mobile_number = $request->mobile_number;
        $deliveryAssociate->vechicle_number = $request->vehicle_number;
        $deliveryAssociate->license_number = $request->license_number;
        $deliveryAssociate->status = $request->status;
        if ($image = $request->file('profile_pic')) 
        {
            $destinationPath = 'uploads/delivery_associates/';
            $profilePic = time() . "." . $image->getClientOriginalName();
            $image->move($destinationPath, $profilePic);
            $deliveryAssociate->profile_pic = $profilePic;
        }
        if ($image = $request->file('vechicle_doc')) 
        {
            $vechicleImage = 'uploads/driver_vehicle_doc/';
            $vechicleDoc = time() . "." . $image->getClientOriginalName();
            $image->move($vechicleImage, $vechicleDoc);
            $deliveryAssociate->vechicle_doc = $vechicleDoc;
        }
        $deliveryAssociate->save();

        return redirect()->route('admin.delivery_associates.index')->with('success', 'Delivery Associate updated successfully.');

    }



}

