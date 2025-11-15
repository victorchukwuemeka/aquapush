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



<!-- ====================== 3. Key Features ====================== -->
<section class="mb-12">
    <h2 class="text-2xl font-semibold text-gray-900 mb-3">3. Key Features</h2>
    <ul class="list-disc ml-6 text-gray-700 leading-relaxed space-y-2">
        <li>Automatic Apache setup</li>
        <li>Automatic PHP + Composer installation</li>
        <li>Environment variable manager</li>
        <li>Access logs for each deployment</li>
        <li>Secure GitHub OAuth login</li>
    </ul>

    <!-- Base64 Dummy Image -->
    <figure class="mt-6">
        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAlgAAAH0CAYAAABq4i4gAAAACXBIWXMAAAsTAAALEwEAmpwYAAAKT2lDQ1BQaG90b3Nob3AgSUNDIHByb2ZpbGUAAHjanVNnVFPpFj333vRCS4iAlEtvUhUIIFJCi4AUkSYqIQkQSoghodkVUcERRUUEG8igiAOOjoCMFVEsDIoK2AfkIaKOg6OIisr74Xuja9a89+bN/rXXPues852zzwfACAyWSDNRNYAMqUIeEeCDx8TG4eQuQIEKJHAAEAizZCFz/SMBAPh+PDwrIsAHvgABeNMLCADATZvAMByH/w/qQplcAYCEAcB0kThLCIAUAEB6jkKmAEBGAYCdmCZTAKAEAGDLY2LjAFAtAGAnf+bTAICd+Jl7AQBblCEVAaCRACATZYhEAGg7AKzPVopFAFgwABRmS8Q5ANgtADBJV2ZIALC3AMDOEAuyAAgMADBRiIUpAAR7AGDIIyN4AISZABRG8lc88SuuEOcqAAB4mbI8uSQ5RYFbCC1xB1dXLh4ozkkXKxQ2YQJhmkAuwnmZGTKBNA/g88wAAKCRFRHgg/P9eM4Ors7ONo62Dl8t6r8G/yJiYuP495gZ+YDqfw5sH6iYCJAe7TlDdBEOv4TVrDwyiBKBWIdFt9s5WtQfQ3p9RwD2rQv8zA1Z3n4AAAAAElFTkSuQmCC" 
             alt="Key features panel"
             class="rounded-lg shadow-lg w-full bg-amber-600 p-8">
        <figcaption class="text-sm text-gray-500 text-center mt-2">
            Feature overview inside the app
        </figcaption>
    </figure>
</section>

<!-- ====================== 4. Authentication ====================== -->
<section class="mb-12">
    <h2 class="text-2xl font-semibold text-gray-900 mb-3">4. Authentication</h2>
    <p class="text-gray-700 leading-relaxed mb-4">
        AquaPush uses GitHub as the authentication provider. Click the button below to
        log in or create an account.
    </p>

    @if(!Auth::check())
        <a href="{{ route('auth.redirect') }}"
           class="inline-flex items-center bg-red-600 text-white py-3 px-8 rounded-lg hover:bg-red-700 transition-all duration-300">
            Sign in with GitHub
        </a>
    @else
        <a href="{{ route('dashboard') }}"
           class="inline-flex items-center bg-red-600 text-white py-3 px-8 rounded-lg hover:bg-red-700 transition-all duration-300">
            Go to Dashboard
        </a>
    @endif

    <!-- Base64 Dummy Image -->
    <figure class="mt-6">
        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAZAAAAGQCAYAAACu7K8IAAAACXBIWXMAAAsTAAALEwEAmpwYAAAKT2lDQ1BQaG90b3Nob3AgSUNDIHByb2ZpbGUAAHjanVNnVFPpFj333vRCS4iAlEtvUhUIIFJCi4AUkSYqIQkQSoghodkVUcERRUUEG8igiAOOjoCMFVEsDIoK2AfkIaKOg6OIisr74Xuja9a89+bN/rXXPues852zzwfACAyWSDNRNYAMqUIeEeCDx8TG4eQuQIEKJHAAEAizZCFz/SMBAPh+PDwrIsAHvgABeNMLCADATZvAMByH/w/qQplcAYCEAcB0kThLCIAUAEB6jkKmAEBGAYCdmCZTAKAEAGDLY2LjAFAtAGAnf+bTAICd+Jl7AQBblCEVAaCRACATZYhEAGg7AKzPVopFAFgwABRmS8Q5ANgtADBJV2ZIALC3AMDOEAuyAAgMADBRiIUpAAR7AGDIIyN4AISZABRG8lc88SuuEOcqAAB4mbI8uSQ5RYFbCC1xB1dXLh4ozkkXKxQ2YQJhmkAuwnmZGTKBNA/g88wAAKCRFRHgg/P9eM4Ors7ONo62Dl8t6r8G/yJiYuP495gZ+YDqfw5sH6iYCJAe7TlDdBEOv4TVrDwyiBKBWIdFt9s5WtQfQ3p9RwD2rQv8zA1Z3n4AAAAAElFTkSuQmCC" 
             alt="GitHub login button"
             class="rounded-lg shadow-lg w-full max-w-md mx-auto bg-red-600 p-6">
        <figcaption class="text-sm text-gray-500 text-center mt-2">
            Login screen with GitHub OAuth
        </figcaption>
    </figure>
</section>

<!-- ====================== 5. Next Steps ====================== -->
<section class="mb-12">
    <h2 class="text-2xl font-semibold text-gray-900 mb-3">5. Next Steps</h2>
    <ul class="list-disc ml-6 text-gray-700 leading-relaxed space-y-2">
        <li>Connect GitHub → Create new server</li>
        <li>Add your Laravel project repository</li>
        <li>Deploy using the AquaPush build pipeline</li>
        <li>Manage logs, environment variables, and workers</li>
    </ul>

    <!-- Base64 Dummy Image -->
    <figure class="mt-6">
        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAlgAAAH0CAYAAABq4i4gAAAACXBIWXMAAAsTAAALEwEAmpwYAAAKT2lDQ1BQaG90b3Nob3AgSUNDIHByb2ZpbGUAAHjanVNnVFPpFj333vRCS4iAlEtvUhUIIFJCi4AUkSYqIQkQSoghodkVUcERRUUEG8igiAOOjoCMFVEsDIoK2AfkIaKOg6OIisr74Xuja9a89+bN/rXXPues852zzwfACAyWSDNRNYAMqUIeEeCDx8TG4eQuQIEKJHAAEAizZCFz/SMBAPh+PDwrIsAHvgABeNMLCADATZvAMByH/w/qQplcAYCEAcB0kThLCIAUAEB6jkKmAEBGAYCdmCZTAKAEAGDLY2LjAFAtAGAnf+bTAICd+Jl7AQBblCEVAaCRACATZYhEAGg7AKzPVopFAFgwABRmS8Q5ANgtADBJV2ZIALC3AMDOEAuyAAgMADBRiIUpAAR7AGDIIyN4AISZABRG8lc88SuuEOcqAAB4mbI8uSQ5RYFbCC1xB1dXLh4ozkkXKxQ2YQJhmkAuwnmZGTKBNA/g88wAAKCRFRHgg/P9eM4Ors7ONo62Dl8t6r8G/yJiYuP495gZ+YDqfw5sH6iYCJAe7TlDdBEOv4TVrDwyiBKBWIdFt9s5WtQfQ3p9RwD2rQv8zA1Z3n4AAAAAElFTkSuQmCC" 
             alt="Next steps wizard"
             class="rounded-lg shadow-lg w-full bg-indigo-600 p-8">
        <figcaption class="text-sm text-gray-500 text-center mt-2">
            Guided wizard after login
        </figcaption>
    </figure>
</section>

</div>
</div>
@endsection