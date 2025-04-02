<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::orderBy('name')->paginate(10);
        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'cpf_cnpj' => 'required|max:18|unique:clients',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|max:15',
            'address' => 'required|max:255',
            'number' => 'nullable|max:20',
            'complement' => 'nullable|max:100',
            'neighborhood' => 'required|max:100',
            'zip_code' => 'required|max:9',
            'city' => 'required|max:100',
            'state' => 'required|max:2',
            'notes' => 'nullable'
        ]);

        Client::create($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Cliente cadastrado com sucesso!');
    }

    public function show(Client $client)
    {
        return response()->json($client);
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'cpf_cnpj' => 'required|max:18|unique:clients,cpf_cnpj,' . $client->id,
            'email' => 'nullable|email|max:255',
            'phone' => 'required|max:15',
            'address' => 'required|max:255',
            'number' => 'nullable|max:20',
            'complement' => 'nullable|max:100',
            'neighborhood' => 'required|max:100',
            'zip_code' => 'required|max:9',
            'city' => 'required|max:100',
            'state' => 'required|max:2',
            'notes' => 'nullable'
        ]);

        $client->update($validated);

        return redirect()->route('clients.index')
            ->with('success', 'Cliente atualizado com sucesso!');
    }

    public function destroy(Client $client)
    {
        if ($client->quotes()->exists()) {
            return redirect()->route('clients.index')
                ->with('error', 'Este cliente não pode ser excluído pois possui orçamentos vinculados.');
        }

        $client->delete();

        return redirect()->route('clients.index')
            ->with('success', 'Cliente excluído com sucesso!');
    }
} 