@extends("layouts.app")
@section("content")
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-16">
<div class="max-w-4xl mx-auto">

<h1 class="text-4xl font-bold text-gray-900 mb-6">
    AquaPush Documentation
</h1>
<p class="text-lg text-gray-600 leading-relaxed mb-10">
    Welcome to the official AquaPush documentation. This guide will walk you through
    connecting your GitHub repository, deploying your Laravel application, and managing
    your server on DigitalOcean — all with zero DevOps experience required.
</p>

<!-- ====================== 1. What is AquaPush? ====================== -->
<section class="mb-12">
    <h2 class="text-2xl font-semibold text-gray-900 mb-3">1. What is AquaPush?</h2>
    <p class="text-gray-700 leading-relaxed mb-4">
        AquaPush is a deployment tool that allows you to deploy Laravel applications
        directly from GitHub to DigitalOcean in minutes. It automatically handles server
        setup, PHP configuration, Composer installation, queue workers, and more.
        All you need for now is your DigitalOcean API key and your SSH key to set up a droplet.
    </p>

    <!-- Base64 Dummy Image -->
    <figure class="mt-6">
        <img src="{{ asset('images/docs/landing.png') }}" 
             alt="AquaPush overview dashboard"
             class="rounded-lg shadow-lg w-full bg-blue-600 p-8">
        <figcaption class="text-sm text-gray-500 text-center mt-2">
            AquaPush dashboard after login 
        </figcaption>
    </figure>
</section>



<!-- ====================== 2. How Deployment Works ====================== -->
<section class="mb-12">
    <h2 class="text-2xl font-semibold text-gray-900 mb-3">2. How Deployment Works</h2>
    <ol class="list-decimal ml-6 text-gray-700 leading-relaxed space-y-3">
        <li>From the Dashboard Click the New Dreoplet Button.</li>
        <!--<li>Select the repository you want to deploy.</li>
        <li>AquaPush sets up your server automatically.</li>
        <li>Your app is deployed with correct Laravel configuration.</li>-->
    </ol>

    <!-- Base64 Dummy Image -->
    <figure class="mt-6">
        <img src="{{ asset('images/docs/dashboard.png')}}" 
             alt="Deployment workflow"
             class="rounded-lg shadow-lg w-full bg-green-600 p-8">
        <figcaption class="text-sm text-gray-500 text-center mt-2">
            Step-by-step deployment flow
        </figcaption>
    </figure>
</section>




<!-- ====================== 2. How Deployment Works ====================== -->
<section class="mb-12">
    <h2 class="text-2xl font-semibold text-gray-900 mb-3">the configuration form</h2>
    <ol class="list-decimal ml-6 text-gray-700 leading-relaxed space-y-3">
        <li>Use your digitalOcean API.</li>
        <li>Input Your SSH key .</li>
        <li>If You Don't Have SSH Key For the Guild For Getting One.</li>
        <li>After You Click The Create Button AquaPush Create All The Needed 
            Software For Deploying Your  Laravel APP.
        </li>
    </ol>

    <!-- Base64 Dummy Image -->
    <figure class="mt-6">
        <img src="{{ asset('images/docs/dropletcreationform.png')}}" 
             alt="Deployment workflow"
             class="rounded-lg shadow-lg w-full bg-green-600 p-8">
        <figcaption class="text-sm text-gray-500 text-center mt-2">
            Step-by-step deployment flow
        </figcaption>
    </figure>
</section>




<!-- ====================== 2. How Deployment Works ====================== -->
<section class="mb-12">
    <h2 class="text-2xl font-semibold text-gray-900 mb-3">Monitor Your Deployments</h2>
    <ol class="list-decimal ml-6 text-gray-700 leading-relaxed space-y-3">
        <li>Keep taps of the setup from the Logs.</li>
        <li>When it is succesful now Check Your Check Your Droplet  With The button Below .</li>
        
    </ol>

    <!-- Base64 Dummy Image -->
    <figure class="mt-6">
        <img src="{{ asset('images/docs/status.png')}}" 
             alt="Deployment workflow"
             class="rounded-lg shadow-lg w-full bg-green-600 p-8">
        <figcaption class="text-sm text-gray-500 text-center mt-2">
            Step-by-step deployment flow
        </figcaption>
    </figure>
</section>


<!-- ====================== 2. How Deployment Works ====================== -->
<section class="mb-12">
    <h2 class="text-2xl font-semibold text-gray-900 mb-3">Deploy Your Laravel App</h2>
    <ol class="list-decimal ml-6 text-gray-700 leading-relaxed space-y-3">
        <li>After  Filling The Form A Submit.</li>
        
    </ol>

    <!-- Base64 Dummy Image -->
    <figure class="mt-6">
        <img src="{{ asset('images/docs/app.png')}}" 
             alt="Deployment workflow"
             class="rounded-lg shadow-lg w-full bg-green-600 p-8">
        <figcaption class="text-sm text-gray-500 text-center mt-2">
            Step-by-step deployment flow
        </figcaption>
    </figure>
</section>





<!-- ====================== 2. How Deployment Works ====================== -->
<section class="mb-12">
    <h2 class="text-2xl font-semibold text-gray-900 mb-3">Drople Page </h2>
    <ol class="list-decimal ml-6 text-gray-700 leading-relaxed space-y-3">
        <li>From The Droplet Page You Can  Create Or Deploy Your Laravel App .</li>
        
    </ol>

    <figure class="mt-6">
        <img src="{{ asset('images/docs/droplet.png')}}" 
             alt="Deployment workflow"
             class="rounded-lg shadow-lg w-full bg-green-600 p-8">
        <figcaption class="text-sm text-gray-500 text-center mt-2">
            Step-by-step deployment flow
        </figcaption>
    </figure>
</section>




<section class="mb-12">
    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg">
        <div class="flex items-start">
            <svg class="w-6 h-6 text-blue-500 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <h3 class="text-lg font-semibold text-blue-900 mb-2">View Your Deployed App</h3>
                <p class="text-blue-800">
                    Copy the IP address above and paste it into your browser's address bar to see your live application.
                </p>
            </div>
        </div>
    </div>
</section>


<!-- ====================== 5. Next Steps ====================== -->
<!--<section class="mb-12">
    <h2 class="text-2xl font-semibold text-gray-900 mb-3">5. Next Steps</h2>
    <ul class="list-disc ml-6 text-gray-700 leading-relaxed space-y-2">
        <li>Connect GitHub → Create new server</li>
        <li>Add your Laravel project repository</li>
        <li>Deploy using the AquaPush build pipeline</li>
        <li>Manage logs, environment variables, and workers</li>
    </ul>-->

    <!-- Base64 Dummy Image -->
    <!--<figure class="mt-6">
        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAlgAAAH0CAYAAABq4i4gAAAACXBIWXMAAAsTAAALEwEAmpwYAAAKT2lDQ1BQaG90b3Nob3AgSUNDIHByb2ZpbGUAAHjanVNnVFPpFj333vRCS4iAlEtvUhUIIFJCi4AUkSYqIQkQSoghodkVUcERRUUEG8igiAOOjoCMFVEsDIoK2AfkIaKOg6OIisr74Xuja9a89+bN/rXXPues852zzwfACAyWSDNRNYAMqUIeEeCDx8TG4eQuQIEKJHAAEAizZCFz/SMBAPh+PDwrIsAHvgABeNMLCADATZvAMByH/w/qQplcAYCEAcB0kThLCIAUAEB6jkKmAEBGAYCdmCZTAKAEAGDLY2LjAFAtAGAnf+bTAICd+Jl7AQBblCEVAaCRACATZYhEAGg7AKzPVopFAFgwABRmS8Q5ANgtADBJV2ZIALC3AMDOEAuyAAgMADBRiIUpAAR7AGDIIyN4AISZABRG8lc88SuuEOcqAAB4mbI8uSQ5RYFbCC1xB1dXLh4ozkkXKxQ2YQJhmkAuwnmZGTKBNA/g88wAAKCRFRHgg/P9eM4Ors7ONo62Dl8t6r8G/yJiYuP495gZ+YDqfw5sH6iYCJAe7TlDdBEOv4TVrDwyiBKBWIdFt9s5WtQfQ3p9RwD2rQv8zA1Z3n4AAAAAElFTkSuQmCC" 
             alt="Next steps wizard"
             class="rounded-lg shadow-lg w-full bg-indigo-600 p-8">
        <figcaption class="text-sm text-gray-500 text-center mt-2">
            Guided wizard after login
        </figcaption>
    </figure>-->
</section>

</div>
</div>
@endsection